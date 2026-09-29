import json
from fontTools.ttLib import TTFont
from fontTools.varLib.instancer import instantiateVariableFont
from fontTools.pens.svgPathPen import SVGPathPen
from fontTools.pens.transformPen import TransformPen
SP="/tmp/claude-0/-home-user-YUPOMA-LARIMIDE/5f868346-f05b-54ce-90bd-d1f9ec6533fb/scratchpad/"
OUT="/home/user/YUPOMA_LARIMIDE/branding/"
parts=json.load(open(SP+"parts.json"))[1:]
fly=[d for d,b in parts if b[1]<419 and b[2]<200]     # бабочка
word=[d for d,b in parts if d not in fly]             # буквы YUPOMA
BF=" ".join(fly); WD=" ".join(word)
INK="#34302D"
font=instantiateVariableFont(TTFont(SP+"Montserrat.ttf"),{"wght":500})
gs=font.getGlyphSet(); cmap=font.getBestCmap(); upm=font["head"].unitsPerEm
def text_path(s,x,y,size,track):
    k=size/upm; out=[]
    for ch in s:
        g=cmap[ord(ch)]; pen=SVGPathPen(gs)
        gs[g].draw(TransformPen(pen,(k,0,0,-k,x,y))); out.append(pen.getCommands())
        x+=gs[g].width*k+track
    return " ".join(out), x
def width(s,size,track): return text_path(s,0,0,size,track)[1]-track
# ширина слова YUPOMA в исходнике: 130..865
def sub(label,size=34,track=26,y=600):
    w=width(label,size,track); x=130+(865-130-w)/2
    return text_path(label,x,y,size,track)[0], x, x+w

# бабочка взлетает: сдвиг вверх-вправо и лёгкий наклон
FLY='transform="translate(58 -58) rotate(-14 131 397)"'
TRAIL='<path d="M150 440 C 150 400, 160 372, 182 350" fill="none" stroke="{c}" stroke-width="4.5" stroke-linecap="round" stroke-dasharray="0.1 12" opacity=".9"/>'
def sport_logo(fn,title,wing,label="SPORT",ink=INK,sub_c=None,bg=None):
    sub_c=sub_c or wing
    sp,x0,x1=sub(label)
    line=f'<path d="M130 588H{x0-24:.0f}M{x1+24:.0f} 588H865" stroke="{sub_c}" stroke-width="2" opacity=".55"/>'
    rect=f'<rect x="60" y="250" width="870" height="400" rx="40" fill="{bg}"/>' if bg else ""
    open(OUT+fn,"w").write(f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="60 250 870 400" width="870" height="400">
  <title>{title}</title>{rect}
  {TRAIL.format(c=wing)}
  <path {FLY} d="{BF}" fill="{wing}" fill-rule="evenodd"/>
  <path d="{WD}" fill="{ink}" fill-rule="evenodd"/>
  {line}
  <path d="{sp}" fill="{sub_c}"/>
</svg>
''')
def icon(fn,title,wing,bg):
    open(OUT+fn,"w").write(f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240" width="240" height="240">
  <title>{title}</title>
  <rect width="240" height="240" rx="56" fill="{bg}"/>
  <g transform="translate(120 120) scale(1.55) rotate(-14) translate(-131 -397)">
    <path d="M95 440 C 100 425, 108 418, 116 412" fill="none" stroke="{wing}" stroke-width="3.2" stroke-linecap="round" stroke-dasharray="0.1 8" opacity=".9"/>
    <path d="{BF}" fill="{wing}" fill-rule="evenodd"/>
  </g>
</svg>
''')
ORANGE="#F26A2E"; ORANGE2="#E4572E"
# градиентная бабочка для основного варианта
open(OUT+"yupoma-original.svg","w").write(f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="60 320 870 230" width="870" height="230">
  <title>YUPOMA</title>
  <path d="{BF} {WD}" fill="{INK}" fill-rule="evenodd"/>
</svg>
''')
sport_logo("yupoma-sport.svg","YUPOMA SPORT",ORANGE)
sport_logo("yupoma-sport-mono.svg","YUPOMA SPORT (mono)",INK)
sport_logo("yupoma-sport-light.svg","YUPOMA SPORT (on orange)","#FFFFFF",ink="#FFFFFF",bg=ORANGE)
sport_logo("yupoma-bio.svg","YUPOMA BIO","#3E9B57",label="BIO")
icon("yupoma-sport-icon.svg","YUPOMA SPORT icon",ORANGE,"#FFF4EC")
icon("yupoma-sport-icon-orange.svg","YUPOMA SPORT icon","#FFFFFF",ORANGE)
