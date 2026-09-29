<?php
/**
 * Page content for YUPOMA SPORT. Placeholders in [BRACKETS] must be filled in by the owner.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function yupoma_pages() {
	$company = '[COMPANY NAME]';
	$nif     = '[NIF/NIE]';
	$address = '[ADDRESS, POSTCODE, CITY, SPAIN]';
	$email   = '[EMAIL]';
	$phone   = '[PHONE / WHATSAPP]';

	return array(

		'home' => array(
			'title'   => 'Home',
			'content' => <<<HTML
<h1>Original sportswear. Delivered fast.</h1>
<p>Footwear, clothing and accessories from Nike, Adidas, ASICS and more — 100% original, shipped from Spain.</p>
<p><a class="wp-element-button" href="/shop/">Shop now</a></p>

<h2>New In</h2>
[products limit="8" columns="4" orderby="date" order="DESC"]

<h2>Shop by sport</h2>
<p><a href="/product-category/men/men-footwear/men-footwear-running/">Running</a> · <a href="/product-category/men/men-footwear/men-footwear-basketball/">Basketball</a> · <a href="/product-category/men/men-footwear/men-footwear-tennis/">Tennis</a> · <a href="/product-category/men/men-footwear/men-footwear-training-gym/">Training &amp; Gym</a> · <a href="/product-category/men/men-footwear/men-footwear-football/">Football</a> · <a href="/product-category/men/men-footwear/men-footwear-lifestyle/">Lifestyle</a></p>

<h2>Bestsellers</h2>
[products limit="8" columns="4" best_selling="true"]

<h2>Why YUPOMA SPORT</h2>
<ul>
<li><strong>100% original</strong> — every product comes from authorised suppliers within the EU.</li>
<li><strong>Fast shipping</strong> — from Spain to your door.</li>
<li><strong>14-day returns</strong> — changed your mind? Send it back.</li>
<li><strong>Secure payment</strong> — cards, PayPal, Apple Pay, Bizum.</li>
</ul>
HTML,
		),

		'brands' => array(
			'title'   => 'Brands',
			'content' => <<<HTML
<h1>Brands</h1>
<p>We work only with original products from the world's leading sports brands.</p>
<h2>Nike</h2>
[products limit="8" columns="4" brand="nike"]
<h2>Adidas</h2>
[products limit="8" columns="4" brand="adidas"]
<h2>ASICS</h2>
[products limit="8" columns="4" brand="asics"]
<p><em>Nike, Adidas and ASICS are trademarks of their respective owners. YUPOMA SPORT is an independent retailer and is not affiliated with or endorsed by these brands.</em></p>
HTML,
		),

		'sale' => array(
			'title'   => 'Sale',
			'content' => <<<HTML
<h1>Sale</h1>
<p>For every discounted item, the reference price shown is the lowest price applied in the 30 days before the discount.</p>
[sale_products limit="24" columns="4"]
HTML,
		),

		'size-guide' => array(
			'title'   => 'Size Guide',
			'content' => <<<HTML
<h1>Size Guide</h1>
<p>Sizes can differ between brands. Measure your foot from heel to the tip of the longest toe and compare with the tables below. If you are between two sizes, we recommend the larger one.</p>

<h2>Nike — footwear (unisex)</h2>
<table><thead><tr><th>Foot (cm)</th><th>EU</th><th>UK</th><th>US M</th><th>US W</th></tr></thead><tbody>
<tr><td>23.5</td><td>37.5</td><td>4.5</td><td>5</td><td>6.5</td></tr>
<tr><td>24.5</td><td>39</td><td>5.5</td><td>6.5</td><td>8</td></tr>
<tr><td>25.5</td><td>40.5</td><td>6.5</td><td>7.5</td><td>9</td></tr>
<tr><td>26.5</td><td>42</td><td>7.5</td><td>8.5</td><td>10</td></tr>
<tr><td>27.5</td><td>43</td><td>8.5</td><td>9.5</td><td>11</td></tr>
<tr><td>28.5</td><td>44.5</td><td>9.5</td><td>10.5</td><td>12</td></tr>
<tr><td>29.5</td><td>46</td><td>10.5</td><td>11.5</td><td>13</td></tr>
</tbody></table>

<h2>Adidas — footwear (unisex)</h2>
<table><thead><tr><th>Foot (cm)</th><th>EU</th><th>UK</th><th>US M</th><th>US W</th></tr></thead><tbody>
<tr><td>23.3</td><td>37 1/3</td><td>4.5</td><td>5</td><td>6</td></tr>
<tr><td>24.5</td><td>39 1/3</td><td>6</td><td>6.5</td><td>7.5</td></tr>
<tr><td>25.5</td><td>40 2/3</td><td>7</td><td>7.5</td><td>8.5</td></tr>
<tr><td>26.5</td><td>42</td><td>8</td><td>8.5</td><td>9.5</td></tr>
<tr><td>27.5</td><td>43 1/3</td><td>9</td><td>9.5</td><td>10.5</td></tr>
<tr><td>28.5</td><td>44 2/3</td><td>10</td><td>10.5</td><td>11.5</td></tr>
<tr><td>29.3</td><td>46</td><td>11</td><td>11.5</td><td>12.5</td></tr>
</tbody></table>

<h2>ASICS — footwear (unisex)</h2>
<table><thead><tr><th>Foot (cm)</th><th>EU</th><th>UK</th><th>US M</th></tr></thead><tbody>
<tr><td>23.5</td><td>37.5</td><td>4.5</td><td>5.5</td></tr>
<tr><td>24.5</td><td>39</td><td>5.5</td><td>6.5</td></tr>
<tr><td>25.5</td><td>40.5</td><td>6.5</td><td>7.5</td></tr>
<tr><td>26.5</td><td>42</td><td>7.5</td><td>8.5</td></tr>
<tr><td>27.5</td><td>43.5</td><td>8.5</td><td>9.5</td></tr>
<tr><td>28.5</td><td>45</td><td>9.5</td><td>10.5</td></tr>
<tr><td>29.5</td><td>46.5</td><td>10.5</td><td>11.5</td></tr>
</tbody></table>

<h2>Clothing (men / women)</h2>
<table><thead><tr><th>Size</th><th>Chest men (cm)</th><th>Chest women (cm)</th><th>Waist women (cm)</th></tr></thead><tbody>
<tr><td>XS</td><td>82–88</td><td>76–83</td><td>60–67</td></tr>
<tr><td>S</td><td>88–96</td><td>83–90</td><td>67–74</td></tr>
<tr><td>M</td><td>96–104</td><td>90–97</td><td>74–81</td></tr>
<tr><td>L</td><td>104–112</td><td>97–104</td><td>81–88</td></tr>
<tr><td>XL</td><td>112–124</td><td>104–114</td><td>88–98</td></tr>
</tbody></table>
<p><em>Tables are indicative. Always check the size information on each product page.</em></p>
HTML,
		),

		'shipping-returns' => array(
			'title'   => 'Shipping & Returns',
			'content' => <<<HTML
<h1>Shipping &amp; Returns</h1>

<h2>Shipping</h2>
<table><thead><tr><th>Destination</th><th>Delivery time</th><th>Cost</th></tr></thead><tbody>
<tr><td>Peninsular Spain</td><td>24–72 h (working days)</td><td>[€X] — free over [€Y]</td></tr>
<tr><td>Balearic Islands</td><td>2–4 working days</td><td>[€X]</td></tr>
<tr><td>Canary Islands, Ceuta, Melilla</td><td>4–8 working days</td><td>[€X] — prices without Spanish VAT; local taxes (IGIC/IPSI) and customs fees may apply and are paid by the customer</td></tr>
<tr><td>European Union</td><td>3–7 working days</td><td>[€X]</td></tr>
</tbody></table>
<p>You will receive a tracking number by email as soon as your order ships.</p>

<h2>Returns — 14-day right of withdrawal</h2>
<p>You may withdraw from your purchase within <strong>14 calendar days</strong> from the day you (or a person you designate) receive the goods, without giving any reason.</p>
<ol>
<li>Tell us by email at {$email}, using the <a href="/right-of-withdrawal/">withdrawal form</a> or the "Withdraw from contract" link at the bottom of every page.</li>
<li>Send the items back within 14 days of notifying us, unused and in their original packaging with labels attached.</li>
<li>We refund the full amount, including the standard initial shipping cost, within 14 days of receiving your withdrawal notice. We may withhold the refund until we have received the goods back.</li>
</ol>
<p>Return shipping costs are paid by the customer [unless stated otherwise]. Refunds are made using the same payment method you used.</p>

<h2>Legal guarantee</h2>
<p>All products have a <strong>3-year legal guarantee</strong> of conformity under Spanish consumer law. If an item is faulty, contact us and we will repair, replace or refund it.</p>

<h2>FAQ</h2>
<p><strong>Are your products original?</strong><br>Yes. All products are 100% original and come from authorised suppliers within the European Union.</p>
<p><strong>Which payment methods do you accept?</strong><br>Visa, Mastercard, PayPal, Apple Pay and Bizum.</p>
<p><strong>Can I exchange a size?</strong><br>Yes. Return the item within 14 days and place a new order for the size you need.</p>
<p><strong>Where is my order?</strong><br>Use the tracking link in your shipping email, or contact us at {$email}.</p>
HTML,
		),

		'about' => array(
			'title'   => 'About & Authenticity',
			'content' => <<<HTML
<h1>About YUPOMA SPORT</h1>
<p>YUPOMA SPORT is part of the YUPOMA group, based in Spain. We bring together the best footwear, clothing and accessories for running, basketball, tennis, training, football and everyday life.</p>
<h2>Authenticity guarantee</h2>
<ul>
<li>Every product we sell is <strong>100% original</strong>.</li>
<li>We buy only from authorised distributors and suppliers within the European Union.</li>
<li>Each order ships with an invoice. If you ever have doubts about a product, contact us — we will show you its origin.</li>
</ul>
<p><em>YUPOMA SPORT is an independent retailer and is not affiliated with, authorised or endorsed by Nike, Adidas or ASICS.</em></p>
HTML,
		),

		'contact' => array(
			'title'   => 'Contact',
			'content' => <<<HTML
<h1>Contact us</h1>
<p>We usually reply within 24 hours on working days.</p>
<ul>
<li>Email: {$email}</li>
<li>Phone / WhatsApp: {$phone}</li>
<li>Address: {$company}, {$address}</li>
</ul>
<p>Complaint forms (hojas de reclamaciones) are available on request.</p>
HTML,
		),

		'legal-notice' => array(
			'title'   => 'Legal Notice',
			'content' => <<<HTML
<h1>Legal Notice (Aviso Legal)</h1>
<p>In compliance with Article 10 of Law 34/2002 on Information Society Services and Electronic Commerce (LSSI-CE), the owner of this website is:</p>
<ul>
<li>Owner: {$company}</li>
<li>NIF/NIE: {$nif}</li>
<li>Address: {$address}</li>
<li>Email: {$email}</li>
<li>[Commercial Registry details, if the owner is a company (S.L.)]</li>
</ul>
<h2>Intellectual and industrial property</h2>
<p>The YUPOMA trademark, logo and website content belong to {$company}. Third-party brand names (Nike, Adidas, ASICS and others) are used only to identify the original products we sell and belong to their respective owners.</p>
<h2>Liability</h2>
<p>We make every effort to keep the information on this website accurate and up to date, but we cannot guarantee that it is free of errors. Prices and availability may change without notice; the price that applies is the one shown at the time of ordering.</p>
<h2>Applicable law</h2>
<p>These terms are governed by Spanish law.</p>
HTML,
		),

		'terms-and-conditions' => array(
			'title'   => 'Terms and Conditions',
			'content' => <<<HTML
<h1>Terms and Conditions of Sale</h1>
<p>These conditions apply to all purchases made on this website, operated by {$company} (NIF {$nif}, {$address}, {$email}). They are governed by Royal Legislative Decree 1/2007 (TRLGDCU) and Law 34/2002 (LSSI-CE).</p>
<h2>1. Ordering</h2>
<p>Add products to your cart, enter your details and press <strong>"Place order and pay"</strong>. The contract is concluded when we send you the order confirmation by email. You can review and correct your details before paying. Orders are available in English.</p>
<h2>2. Prices</h2>
<p>All prices are in euros and <strong>include VAT (IVA 21%)</strong>. Shipping costs are shown before payment. For the Canary Islands, Ceuta and Melilla prices are shown without Spanish VAT; local taxes and customs fees may apply.</p>
<h2>3. Payment</h2>
<p>Card (Visa, Mastercard), PayPal, Apple Pay and Bizum. Payments are processed securely by our payment providers; we do not store your card details.</p>
<h2>4. Delivery</h2>
<p>See <a href="/shipping-returns/">Shipping &amp; Returns</a>. The maximum delivery time is 30 days from the order.</p>
<h2>5. Right of withdrawal</h2>
<p>You have 14 calendar days from receipt of the goods to withdraw without giving any reason. See the <a href="/right-of-withdrawal/">Right of Withdrawal</a> page and model form.</p>
<h2>6. Legal guarantee</h2>
<p>Products have a 3-year legal guarantee of conformity (Articles 114 and following, TRLGDCU).</p>
<h2>7. Complaints</h2>
<p>Contact us at {$email}. Complaint forms are available on request. You may also contact your local consumer office (OMIC) or the consumer arbitration system.</p>
<h2>8. Applicable law and jurisdiction</h2>
<p>Spanish law applies. For consumers, the courts of the consumer's place of residence are competent.</p>
HTML,
		),

		'right-of-withdrawal' => array(
			'title'   => 'Right of Withdrawal',
			'content' => <<<HTML
<h1>Right of Withdrawal — Withdraw from contract</h1>
<p>You have the right to withdraw from this contract within 14 days without giving any reason. The withdrawal period expires 14 days after the day on which you, or a third party indicated by you (other than the carrier), acquire physical possession of the goods.</p>
<p>To exercise the right of withdrawal, send us a clear statement (for example an email to {$email}) or use the model form below. It is enough to send your notice before the withdrawal period expires.</p>
<h2>Effects of withdrawal</h2>
<p>We will refund all payments received from you, including standard delivery costs, without undue delay and no later than 14 days from the day we are informed of your decision, using the same means of payment. We may withhold the refund until we have received the goods back or you have supplied proof of having sent them back. You must send back the goods within 14 days and bear the direct cost of returning them. You are only liable for any diminished value of the goods resulting from handling other than what is necessary to establish their nature and characteristics.</p>
<h2>Model withdrawal form</h2>
<p>(Complete and return this form only if you wish to withdraw from the contract.)</p>
<p>To: {$company}, {$address}, {$email}</p>
<p>I/We (*) hereby give notice that I/We (*) withdraw from my/our (*) contract of sale of the following goods (*):<br>
Ordered on (*) / received on (*):<br>
Order number:<br>
Name of consumer(s):<br>
Address of consumer(s):<br>
Signature of consumer(s) (only if this form is notified on paper):<br>
Date:</p>
<p>(*) Delete as appropriate.</p>
<p><a class="wp-element-button" href="mailto:{$email}?subject=Withdrawal%20from%20contract%20-%20Order%20%23">Withdraw from contract by email</a></p>
HTML,
		),

		'privacy-policy' => array(
			'title'   => 'Privacy Policy',
			'content' => <<<HTML
<h1>Privacy Policy</h1>
<p>This policy explains how we process your personal data under Regulation (EU) 2016/679 (GDPR) and Organic Law 3/2018 (LOPDGDD).</p>
<h2>Data controller</h2>
<p>{$company}, NIF {$nif}, {$address}. Contact: {$email}.</p>
<h2>What data we process and why</h2>
<table><thead><tr><th>Purpose</th><th>Data</th><th>Legal basis</th><th>Retention</th></tr></thead><tbody>
<tr><td>Processing and delivering orders, invoicing</td><td>Name, address, email, phone, order details</td><td>Performance of a contract; legal obligation</td><td>As long as required by tax and commercial law (up to 6 years)</td></tr>
<tr><td>Customer service and withdrawals</td><td>Contact details, messages</td><td>Performance of a contract</td><td>Until the request is resolved and legal periods expire</td></tr>
<tr><td>Newsletter</td><td>Email</td><td>Your consent (you can withdraw it at any time)</td><td>Until you unsubscribe</td></tr>
<tr><td>Customer account</td><td>Login details, order history</td><td>Your consent / contract</td><td>Until you delete the account</td></tr>
</tbody></table>
<h2>Recipients</h2>
<p>Payment providers (Stripe, PayPal), shipping companies, hosting provider (Hostinger) and our tax advisor, only as needed for the purposes above. Some providers may process data outside the EEA under the safeguards of the GDPR (adequacy decisions or standard contractual clauses).</p>
<h2>Your rights</h2>
<p>You can request access, rectification, erasure, restriction, portability and objection by writing to {$email}. You also have the right to lodge a complaint with the Spanish Data Protection Agency (AEPD), www.aepd.es.</p>
HTML,
		),

		'cookie-policy' => array(
			'title'   => 'Cookie Policy',
			'content' => <<<HTML
<h1>Cookie Policy</h1>
<p>This website uses cookies in accordance with Article 22.2 of Law 34/2002 (LSSI-CE) and the guidance of the Spanish Data Protection Agency (AEPD).</p>
<h2>Types of cookies</h2>
<ul>
<li><strong>Technical (necessary):</strong> cart, checkout, login, security. They do not require consent.</li>
<li><strong>Analytics:</strong> help us understand how the site is used. Used only with your consent.</li>
<li><strong>Marketing:</strong> used to show relevant ads. Used only with your consent.</li>
</ul>
<h2>Managing your consent</h2>
<p>When you first visit the site you can accept or reject non-essential cookies — both options are equally visible. You can change your choice at any time via the "Cookie settings" link or in your browser settings.</p>
<p>[List of cookies used on this website — generated by the cookie consent plugin.]</p>
HTML,
		),
	);
}
