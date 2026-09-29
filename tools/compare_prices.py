"""Compare prices of the same products on yupoma.com and cosmeticos24h.com.

Both stores run on Shopify, so the full catalogue is read from /products.json.
Products are matched by barcode (EAN) first, then by brand + normalized title.

Usage:  python3 compare_prices.py            -> writes price_comparison.csv
"""
import csv
import json
import re
import sys
import time
import unicodedata
import urllib.request

STORES = {"yupoma": "https://yupoma.com", "c24h": "https://cosmeticos24h.com"}
UA = {"User-Agent": "Mozilla/5.0 (price-comparison)"}


def fetch_all(base):
    items, page = [], 1
    while True:
        url = f"{base}/products.json?limit=250&page={page}"
        with urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=30) as r:
            batch = json.load(r)["products"]
        if not batch:
            return items
        items += batch
        page += 1
        time.sleep(0.5)


def norm(s):
    s = unicodedata.normalize("NFKD", s or "").encode("ascii", "ignore").decode().lower()
    s = re.sub(r"\b(\d+)\s*(ml|gr|g)\b", r"\1\2", s)
    return " ".join(re.findall(r"[a-z0-9]+", s))


def variants(products):
    for p in products:
        for v in p.get("variants", []):
            title = p["title"] if v.get("title") in (None, "Default Title") else f'{p["title"]} {v["title"]}'
            yield {
                "brand": p.get("vendor", ""),
                "title": title,
                "key": norm(f'{p.get("vendor", "")} {title}'),
                "barcode": (v.get("barcode") or "").strip(),
                "price": float(v["price"]),
                "available": v.get("available", True),
                "url": p["handle"],
            }


def main():
    data = {name: list(variants(fetch_all(base))) for name, base in STORES.items()}
    mine = data["yupoma"]
    brands = {norm(v["brand"]) for v in mine}
    theirs = [v for v in data["c24h"] if norm(v["brand"]) in brands]
    by_ean = {v["barcode"]: v for v in theirs if v["barcode"]}
    by_key = {v["key"]: v for v in theirs}

    rows = []
    for m in mine:
        t = by_ean.get(m["barcode"]) if m["barcode"] else None
        t = t or by_key.get(m["key"])
        if not t:
            continue
        diff = m["price"] - t["price"]
        rows.append({
            "brand": m["brand"], "product": m["title"],
            "yupoma_eur": f'{m["price"]:.2f}', "cosmeticos24h_eur": f'{t["price"]:.2f}',
            "diff_eur": f"{diff:+.2f}", "diff_pct": f'{diff / t["price"] * 100:+.1f}',
            "cheaper_at": "YUPOMA" if diff < 0 else ("cosmeticos24h" if diff > 0 else "same"),
            "match": "EAN" if m["barcode"] and m["barcode"] == t["barcode"] else "name",
            "yupoma_url": f'{STORES["yupoma"]}/products/{m["url"]}',
            "c24h_url": f'{STORES["c24h"]}/products/{t["url"]}',
        })
    rows.sort(key=lambda r: float(r["diff_pct"]))
    out = sys.argv[1] if len(sys.argv) > 1 else "price_comparison.csv"
    with open(out, "w", newline="", encoding="utf-8-sig") as f:
        w = csv.DictWriter(f, fieldnames=list(rows[0]) if rows else ["brand"], delimiter=";")
        w.writeheader()
        w.writerows(rows)
    cheaper = sum(r["cheaper_at"] == "YUPOMA" for r in rows)
    print(f"yupoma: {len(mine)} variants, shared brands at c24h: {len(theirs)}, matched: {len(rows)}, "
          f"cheaper at YUPOMA: {cheaper} -> {out}")


if __name__ == "__main__":
    main()
