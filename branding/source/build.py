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


AX=136   # ось тела бабочки (точка, где сходятся крылья)
FLAP="""<style>
  .wings{transform-origin:136px 412px;animation:flap 4s ease-in-out infinite}
  @keyframes flap{0%,40%,100%{transform:scaleX(1)}8%,24%{transform:scaleX(.45)}16%,32%{transform:scaleX(1)}}
  @media (prefers-reduced-motion:reduce){.wings{animation:none}}
</style>"""
def logo(fn,title,wing=INK,ink=INK,label="SPORT",sub_c=None,animated=False):
    sub_c=sub_c or ink
    sp,x0,x1=sub(label)
    line=f'<path d="M130 588H{x0-24:.0f}M{x1+24:.0f} 588H865" stroke="{sub_c}" stroke-width="2" opacity=".45"/>'
    open(OUT+fn,"w").write(f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="60 320 870 300" width="870" height="300">
  <title>{title}</title>{FLAP if animated else ""}
  <path class="wings" d="{BF}" fill="{wing}" fill-rule="evenodd"/>
  <path d="{WD}" fill="{ink}" fill-rule="evenodd"/>
  {line}
  <path d="{sp}" fill="{sub_c}"/>
</svg>
""")
YL=[d for d,b in parts if b[0]<210 and b[1]>=419]   # только буква Y
def icon(fn,title,wing=INK,bg="#F7F4F1",animated=False):
    open(OUT+fn,"w").write(f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240" width="240" height="240">
  <title>{title}</title>{FLAP if animated else ""}
  <rect width="240" height="240" rx="56" fill="{bg}"/>
  <g transform="translate(120 122) scale(1.15) translate(-146 -438)">
    <path class="wings" d="{BF}" fill="{wing}" fill-rule="evenodd"/>
    <path d="{" ".join(YL)}" fill="{INK}" fill-rule="evenodd"/>
  </g>
</svg>
""")
open(OUT+"yupoma-original.svg","w").write(f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="60 320 870 230" width="870" height="230">
  <title>YUPOMA</title>
  <path d="{BF} {WD}" fill="{INK}" fill-rule="evenodd"/>
</svg>
""")
OR="#E8622C"
logo("yupoma-sport.svg","YUPOMA SPORT")
logo("yupoma-sport-animated.svg","YUPOMA SPORT",animated=True)
logo("yupoma-sport-color.svg","YUPOMA SPORT",wing=OR,sub_c=OR)
logo("yupoma-sport-color-animated.svg","YUPOMA SPORT",wing=OR,sub_c=OR,animated=True)
icon("yupoma-sport-icon.svg","YUPOMA SPORT icon",animated=True)
icon("yupoma-sport-icon-color.svg","YUPOMA SPORT icon",wing=OR,animated=True)
logo("yupoma-bio.svg","YUPOMA BIO",wing="#3E9B57",label="BIO",sub_c="#3E9B57")
