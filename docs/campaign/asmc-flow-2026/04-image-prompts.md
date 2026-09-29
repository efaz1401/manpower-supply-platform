# 04 — Image Prompts (Nano Banana)

Every still in this file is generated with **Nano Banana** inside Flow, then used as an **ingredient** or a **start/end frame** for Veo 3.1.

**Do this stage completely before generating a single video clip.** Images are far cheaper than video. Every hour spent locking plates here saves five hours of regenerating drifted clips later.

---

## Asset build order

```
STEP 1  Style plate                 STYLE.png                    (1 image)
STEP 2  Location plates             LOC-*.png                    (5 images)
STEP 3  Character sheets            SHEET-*.png                  (3 images)
STEP 4  Character ingredient plates CHAR-*.png                   (3 images)
        ↑ APPROVAL GATE — do not proceed until all three faces are locked
STEP 5  Combined plates             PAIR-*.png / TRIO-ALL.png     (3 images)
STEP 6  Shot start frames           FRAME-##-START.png            (as needed)
```

Steps 1–3 are in `02-location-bible.md` and `01-character-bible.md` — the prompts live with their specs, not duplicated here. This file covers **Steps 4–6** plus the workflow rules.

---

## §1 — Global rules for every image prompt

Append these to every prompt in this file. They are the difference between usable plates and unusable ones.

```
Photorealistic. 16:9. No text, no lettering, no signage, no numbers, no logos, no
watermark, no readable writing on clothing or equipment. No AI artefacts, no extra fingers,
no duplicated limbs.
```

And for anything containing a screen:
```
All monitors and screens dark, switched off, or glowing with soft indistinct blue light —
no user interface, no readable display content.
```

**Why "no text" appears on every single prompt:** Nano Banana renders Latin text passably and Arabic script very poorly. A garbled Arabic word on a hard hat or a fake-looking sign will be the only thing your Saudi audience sees. There is no upside to letting the model try.

---

## §2 — Character ingredient plates (Step 4)

The three prompts are in `01-character-bible.md`. Reproduced here as a checklist only:

| File | Source prompt | Approval criteria |
|---|---|---|
| `CHAR-KHALID.png` | `01-character-bible.md` → Character 1 → ingredient-plate prompt | White hard hat, full trimmed beard, glasses on brim, blank vest, calm neutral |
| `CHAR-RAVI.png` | `01-character-bible.md` → Character 2 → ingredient-plate prompt | Orange hard hat, navy coveralls, gloves on, welding helmet tipped up, blank coveralls |
| `CHAR-FAISAL.png` | `01-character-bible.md` → Character 3 → ingredient-plate prompt | White thobe + folded white ghutra, **no black agal**, grey vest, grey hard hat |

### The approval gate

Generate **4 variants of each** plate. Put them side by side. Pick the one where the face is most *ordinary* — not the most handsome or most dramatic. Distinctive-but-plausible faces hold across generations; idealised faces drift toward a generic model look, and dramatic faces drift toward caricature.

Then run this test before proceeding: generate one throwaway 4s Veo clip per character using only `CHAR-X.png` + `STYLE.png` and the prompt `the man stands still and turns his head slowly to look to his left`. **If the face survives a head turn, the plate is good.** If it changes, regenerate the plate — do not proceed and hope.

---

## §3 — Combined plates (Step 5)

These exist purely to beat the 3-ingredient cap. Build them **only after** all three single plates pass the gate, and build them by **uploading the approved single plates as inputs to Nano Banana** so the faces carry over rather than being re-imagined from text.

### `PAIR-KHALID-RAVI.png` — for Shot 12

```
Using the two attached reference portraits, place both men standing side by side, full
body, three-quarter view, facing camera, on a soft neutral grey seamless background, even
soft studio lighting.

Man on the left: Khalid, a 38-year-old Saudi man, medium-brown skin, short black hair, full
neatly trimmed black beard, calm steady dark brown eyes, strong straight nose, lean athletic
build, 178cm tall. He wears a white ASMC hi-vis safety vest with reflective silver bands
over a grey long-sleeve work shirt, a clean white hard hat, and clear safety glasses pushed
up on the brim. A black lanyard with a plain ID card hangs at his chest.

Man on the right: Ravi, a 32-year-old South Asian man, warm brown skin, short black hair,
clean-shaven with a faint moustache shadow, focused dark eyes, sturdy compact build, 172cm
tall. He wears navy-blue ASMC work coveralls with reflective bands at the sleeves, an orange
hard hat, heavy scuffed leather work gloves, and steel-toe boots. A welding helmet is tipped
up on his forehead.

Preserve both faces exactly as in the reference images. Neutral expressions.
Photorealistic, 16:9, no text, no lettering, no logos, no readable writing on clothing, no
watermark.
```

### `PAIR-KHALID-FAISAL.png` — for Shot 14

```
Using the two attached reference portraits, place both men standing side by side, full
body, three-quarter view, facing camera, on a soft neutral grey seamless background, even
soft studio lighting.

Man on the left: Khalid, a 38-year-old Saudi man, medium-brown skin, short black hair, full
neatly trimmed black beard, calm steady dark brown eyes, strong straight nose, lean athletic
build, 178cm tall. He wears a white ASMC hi-vis safety vest with reflective silver bands
over a grey long-sleeve work shirt, a clean white hard hat, and clear safety glasses pushed
up on the brim. A black lanyard with a plain ID card hangs at his chest.

Man on the right: Faisal, a 45-year-old Saudi man, olive-brown skin, short greying black
hair, short salt-and-pepper beard, sharp intelligent eyes with faint lines, solid build,
180cm tall. He wears a crisp white thobe with a folded white ghutra headscarf, and over it a
plain grey hi-vis site vest and a grey hard hat.

Preserve both faces exactly as in the reference images. Measured neutral expressions.
Photorealistic, 16:9, no text, no lettering, no logos, no readable writing on clothing, no
black agal cord on the headscarf, no watermark.
```

### `TRIO-ALL.png` — for Shot 15

```
Using the three attached reference portraits, place all three men standing in a loose line
side by side, full body, three-quarter view, facing camera, on a soft neutral grey seamless
background, even soft studio lighting.

Left: Khalid, a 38-year-old Saudi man, medium-brown skin, short black hair, full neatly
trimmed black beard, calm steady dark brown eyes, lean athletic build, 178cm tall, in a
white ASMC hi-vis safety vest with reflective silver bands over a grey long-sleeve work
shirt, a clean white hard hat, and clear safety glasses pushed up on the brim.

Centre: Faisal, a 45-year-old Saudi man, olive-brown skin, short greying black hair, short
salt-and-pepper beard, sharp intelligent eyes, solid build, 180cm tall, in a crisp white
thobe with a folded white ghutra headscarf, and over it a plain grey hi-vis site vest and a
grey hard hat.

Right: Ravi, a 32-year-old South Asian man, warm brown skin, short black hair, clean-shaven
with a faint moustache shadow, sturdy compact build, 172cm tall, in navy-blue ASMC work
coveralls with reflective bands at the sleeves, an orange hard hat, heavy scuffed leather
work gloves, and steel-toe boots, welding helmet tipped up on his forehead.

Preserve all three faces exactly as in the reference images. Neutral expressions.
Photorealistic, 16:9, no text, no lettering, no logos, no readable writing on clothing, no
black agal cord, no watermark.
```

> **Faisal is placed in the centre deliberately.** The client sits between the supervisor and the tradesman — ASMC's people on both sides of him. It is a compositional argument, and it costs nothing to make.

---

## §4 — Shot start frames (Step 6)

Not every shot needs a start frame. Use them where composition matters more than motion freedom.

| Shot | Needs start frame? | Why |
|---|---|---|
| 01 | Optional | Consider a **real drone plate** instead — see `02-location-bible.md` |
| 02 | No | Simple static, let Veo compose |
| 03 | **Yes** | Faisal's first appearance — lock the blind-light and his position |
| 04 | **Yes** | The "empty site" composition is the point and Veo will fill it if unguided |
| 05 | No | Tight insert, low risk |
| 06 | **Yes** | Khalid's first appearance |
| 07 | **Yes** | Hands-and-folders insert needs exact staging |
| 08 | No | Chain from Shot 06's saved frame instead |
| 09 | **Yes** | The hero shot of the film — do not leave it to chance |
| 10 | No | Tracking shot needs motion freedom |
| 11 | No | Crane action, let Veo drive it |
| 12 | **Yes** | Two-shot staging |
| 13 | No | Crane-up reveal needs freedom |
| 14 | **Yes** | Two-shot + handshake staging |
| 15 | **Yes** | Final composition, must be exact |

### `FRAME-03-START.png`

```
Photorealistic cinematic still, medium shot: Faisal, a 45-year-old Saudi man, olive-brown
skin, short greying black hair, short salt-and-pepper beard, sharp intelligent eyes with
faint lines, solid build, wearing a crisp white thobe with a folded white ghutra headscarf,
no site vest and no hard hat, standing behind a desk and looking down at a large printed
construction drawing.

A functional site project office in Saudi Arabia: a large printed construction drawing
spread across a laminate desk, a desktop monitor turned away from camera, a hard hat resting
on a filing cabinet, aluminium-framed window with dusty vertical blinds half open casting
hard slatted light across him, plain off-white walls, a wall-mounted programme chart.

Documentary-industrial cinematography, shot on ARRI Alexa, warm interior daylight, cool grey
shadows, gentle film grain, restrained editorial grade.

16:9, drawings and charts blurred and illegible, monitor dark and switched off, no readable
text, no logos, no black agal cord on the headscarf, no watermark.
```

### `FRAME-04-START.png`

```
Photorealistic cinematic wide shot of an under-staffed, almost empty construction site.
Small in the frame at the lower left, seen from behind and to the side: Faisal, a
45-year-old Saudi man in a crisp white thobe with a folded white ghutra headscarf, and over
it a plain grey hi-vis site vest and a grey hard hat, standing still and looking up at the
structure.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf, Saudi Arabia:
exposed concrete columns and floor slabs four storeys up, steel scaffolding with green
safety mesh standing idle and unused, rebar bundles and stacked concrete blocks on graded
sand ground, a stationary yellow mobile crane, low date palms and flat sand-coloured
buildings beyond the site fence, mid-morning high hard sunlight, short shadows, fine
airborne dust.

Only two other workers visible, very distant and heavily out of focus, faces not visible.
The site is conspicuously quiet and empty.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes,
warm desert daylight, dust-ochre midtones, gentle film grain, restrained editorial grade.

16:9, no text, no signage, no logos, no crowds, no busy activity, no black agal cord, no
watermark.
```

### `FRAME-06-START.png`

```
Photorealistic cinematic medium shot: Khalid, a 38-year-old Saudi man, medium-brown skin,
short black hair, full neatly trimmed black beard, calm steady dark brown eyes, strong
straight nose, lean athletic build, wearing a white ASMC hi-vis safety vest with reflective
silver bands over a grey long-sleeve work shirt, a clean white hard hat, and clear safety
glasses pushed up on the brim, with a black lanyard and plain ID card at his chest. He
stands at a desk with a phone held to his right ear and a pen in his left hand over an open
notebook.

The ASMC operations room in Al Hofuf: a clean modern office with a long desk, three large
dark monitors, a wall-mounted map of Saudi Arabia with no readable labels, neat stacks of
document folders, a black desk phone, warm cream walls and near-black joinery, soft daylight
from a window on the left.

Documentary-industrial cinematography, shot on ARRI Alexa, soft warm interior light, cool
grey shadows, gentle film grain, restrained editorial grade.

16:9, all monitors dark and switched off with no user interface visible, map labels
illegible, no readable text, no logos, no writing on the vest or hard hat, no watermark.
```

### `FRAME-07-START.png`

```
Photorealistic cinematic extreme close-up, high angle looking straight down at a desk. Only
a man's hands and forearms are in frame, in grey long-sleeve work shirt cuffs. The hands are
laying three plain manila document folders side by side on the desk surface, the nearest one
open.

The ASMC operations room in Al Hofuf: warm cream desk surface, neat stacks of document
folders at the edge of frame, soft daylight from the left.

Shallow depth of field, sharp on the hands and the nearest folder. Documentary-industrial
cinematography, shot on ARRI Alexa, soft warm interior light, gentle film grain, restrained
editorial grade.

16:9, all documents blank and blurred with no readable text, no printing, no lettering, no
logos, no watermark.
```

### `FRAME-09-START.png`

```
Photorealistic cinematic close-up: Ravi, a 32-year-old South Asian man, warm brown skin,
short black hair, clean-shaven with a faint moustache shadow, sturdy compact build, wearing
navy-blue ASMC work coveralls with reflective bands at the sleeves, an orange hard hat, and
heavy scuffed leather work gloves, with his welding helmet lowered down over his face. He is
bent over a steel workbench holding a welding torch to a clamped steel plate, about to strike
an arc.

A steel fabrication workshop bay: a heavy steel workbench with clamped steel plate, a large
roller shutter door half open at the rear letting a hard shaft of dusty sunlight across the
floor, coiled welding cable, gas cylinders chained upright to the wall, dark soot-marked
concrete floor, tool boards in shadow.

Documentary-industrial cinematography, shot on ARRI Alexa, very high contrast, hard shafted
sunlight, deep steel-grey shadows, gentle film grain, subtle halation, restrained editorial
grade.

16:9, no text, no logos, no writing on the coveralls or hard hat, no watermark.
```

### `FRAME-12-START.png`

Use `PAIR-KHALID-RAVI.png` as an input reference to this generation so the faces carry.

```
Using the attached two-person reference image, generate a photorealistic cinematic two-shot
at the edge of a concrete floor slab four storeys up. Both men stand in profile facing each
other, three-quarter to camera, Khalid on the left holding a clipboard, Ravi on the right
with a coil of cable over his shoulder.

Khalid: a 38-year-old Saudi man, medium-brown skin, full neatly trimmed black beard, lean
athletic build, in a white ASMC hi-vis safety vest with reflective silver bands over a grey
long-sleeve work shirt, a clean white hard hat, clear safety glasses pushed up on the brim.

Ravi: a 32-year-old South Asian man, warm brown skin, clean-shaven with a faint moustache
shadow, sturdy compact build, in navy-blue ASMC work coveralls with reflective bands at the
sleeves, an orange hard hat, heavy scuffed leather work gloves, welding helmet tipped up on
his forehead.

Behind them: steel scaffolding with green safety mesh, exposed concrete columns, a yellow
mobile crane and low date palms far below and beyond, late-morning strong directional
sunlight, fine airborne dust. Anonymous background workers heavily out of focus, faces not
visible.

Preserve both faces exactly as in the reference image. Documentary-industrial cinematography,
shot on ARRI Alexa with vintage anamorphic primes, warm desert daylight, dust-ochre
midtones, gentle film grain, restrained editorial grade.

16:9, no text, no signage, no logos, no readable writing on clothing, no watermark.
```

### `FRAME-14-START.png`

Use `PAIR-KHALID-FAISAL.png` as an input reference.

```
Using the attached two-person reference image, generate a photorealistic cinematic two-shot,
both men walking side by side in profile across a construction site, seen from the side at
chest height, mid-stride.

Khalid on the right: a 38-year-old Saudi man, medium-brown skin, full neatly trimmed black
beard, lean athletic build, in a white ASMC hi-vis safety vest with reflective silver bands
over a grey long-sleeve work shirt, a clean white hard hat, clear safety glasses pushed up on
the brim.

Faisal on the left: a 45-year-old Saudi man, olive-brown skin, short greying black hair,
short salt-and-pepper beard, solid build, in a crisp white thobe with a folded white ghutra
headscarf, and over it a plain grey hi-vis site vest and a grey hard hat. His right hand is
raised, gesturing up at the structure.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf: exposed concrete
columns and floor slabs, steel scaffolding with green safety mesh, a yellow mobile crane,
graded sand ground, late afternoon golden hour, long warm shadows, low amber sun flaring
behind them, fine airborne dust. Anonymous background workers in hard hats and hi-vis,
heavily out of focus, faces not visible.

Preserve both faces exactly as in the reference image. Documentary-industrial cinematography,
shot on ARRI Alexa with vintage anamorphic primes, warm golden hour light, dust-ochre
midtones, gentle film grain, subtle halation, restrained editorial grade.

16:9, no text, no signage, no logos, no readable writing on clothing, no black agal cord, no
watermark.
```

### `FRAME-15-START.png`

Use `TRIO-ALL.png` as an input reference.

```
Using the attached three-person reference image, generate a photorealistic cinematic wide
shot. All three men stand in a loose line in the mid-ground, backs three-quarter to camera,
looking up and away from the lens at the completed upper floors of the structure. They are
not looking at the camera. Small in a wide frame, low sun behind them.

Left, in a white ASMC hi-vis safety vest and clean white hard hat: Khalid, a 38-year-old
Saudi man, lean athletic build. Centre, in a crisp white thobe with folded white ghutra
headscarf under a plain grey hi-vis site vest and grey hard hat: Faisal, a 45-year-old Saudi
man, solid build. Right, in navy-blue ASMC work coveralls and an orange hard hat: Ravi, a
32-year-old South Asian man, sturdy compact build.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf: exposed concrete
columns and completed floor slabs four storeys up, steel scaffolding with green safety mesh,
a yellow mobile crane, low date palms and flat sand-coloured buildings beyond the site fence,
late afternoon golden hour, very long warm shadows stretching toward camera, low amber sun,
fine airborne dust.

Preserve all three figures' wardrobe and build exactly as in the reference image.
Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes,
warm golden hour light, dust-ochre midtones, gentle film grain, subtle halation, restrained
editorial grade.

16:9, no text, no signage, no logos, no readable writing on clothing, no black agal cord, no
watermark.
```

---

## §5 — End frames

Only two shots use **Frames to Video** (start *and* end frame). Everywhere else, a start frame plus a motion description is enough and gives Veo room to produce natural movement.

| Shot | Start | End | Purpose |
|---|---|---|---|
| 13 | ground-level detail | wide golden-hour reveal | Forces the crane-up reveal to actually arrive somewhere |
| 14 | `FRAME-14-START.png` | handshake two-shot | Guarantees the handshake happens and is framed properly |

### `FRAME-13-START.png` — low, tight

```
Photorealistic cinematic low shot at ground level, camera almost on the graded sand: the
base of a steel scaffold leg and a stack of rebar bundles in sharp foreground focus, the
concrete structure rising out of focus behind, late afternoon golden hour, long warm shadows
across the sand, low amber sun, fine airborne dust. No people in focus.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes,
warm golden hour light, dust-ochre midtones, gentle film grain, restrained editorial grade.

16:9, no text, no signage, no logos, no watermark.
```

### `FRAME-13-END.png` — high, wide

```
Photorealistic cinematic high wide shot looking across a fully active construction site from
above rooftop height: a mid-rise reinforced-concrete structure four storeys up with crews
working on multiple levels, steel scaffolding with green safety mesh, a yellow mobile crane
mid-lift, graded sand ground, low date palms and flat sand-coloured buildings beyond the site
fence, late afternoon golden hour, very long warm shadows, low amber sun, fine airborne dust.

All visible workers wear hard hats and hi-vis vests, anonymous and out of focus, faces not
visible.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes,
warm golden hour light, dust-ochre midtones, gentle film grain, subtle halation, restrained
editorial grade.

16:9, no text, no signage, no logos, no watermark.
```

### `FRAME-14-END.png` — the handshake

```
Using the attached two-person reference image, generate a photorealistic cinematic two-shot:
the two men have stopped walking and are shaking hands, seen from the side at chest height,
both in three-quarter profile, a brief professional handshake, measured expressions, not
grinning.

Khalid on the right: a 38-year-old Saudi man, medium-brown skin, full neatly trimmed black
beard, in a white ASMC hi-vis safety vest and clean white hard hat with clear safety glasses
pushed up on the brim. Faisal on the left: a 45-year-old Saudi man, olive-brown skin, short
salt-and-pepper beard, in a crisp white thobe with folded white ghutra headscarf under a
plain grey hi-vis site vest and grey hard hat.

Behind them: exposed concrete columns, steel scaffolding with green safety mesh, a yellow
mobile crane, late afternoon golden hour, long warm shadows, low amber sun flaring behind
them.

Preserve both faces exactly as in the reference image. Documentary-industrial cinematography,
shot on ARRI Alexa with vintage anamorphic primes, warm golden hour light, gentle film grain,
subtle halation, restrained editorial grade.

16:9, no text, no signage, no logos, no readable writing on clothing, no black agal cord, no
watermark.
```

---

## §6 — Full asset manifest

Tick these off before opening `05-video-prompts.md`.

```
[ ] STYLE.png
[ ] LOC-DESERT.png
[ ] LOC-SITE.png
[ ] LOC-OFFICE-CLIENT.png
[ ] LOC-OPS.png
[ ] LOC-WORKSHOP.png
[ ] SHEET-KHALID.png          (reference only, not used as ingredient)
[ ] SHEET-RAVI.png
[ ] SHEET-FAISAL.png
[ ] CHAR-KHALID.png           ← head-turn test passed
[ ] CHAR-RAVI.png             ← head-turn test passed
[ ] CHAR-FAISAL.png           ← head-turn test passed
[ ] PAIR-KHALID-RAVI.png
[ ] PAIR-KHALID-FAISAL.png
[ ] TRIO-ALL.png
[ ] FRAME-03-START.png
[ ] FRAME-04-START.png
[ ] FRAME-06-START.png
[ ] FRAME-07-START.png
[ ] FRAME-09-START.png
[ ] FRAME-12-START.png
[ ] FRAME-13-START.png
[ ] FRAME-13-END.png
[ ] FRAME-14-START.png
[ ] FRAME-14-END.png
[ ] FRAME-15-START.png
```

**26 images.** Budget 3–4 generations each for the character plates and 2 each for the rest — roughly **60–70 image generations**. Images are cheap. This is the right place to spend.
