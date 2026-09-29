# 05 — Video Prompts (Veo 3.1 / Google Flow)

15 shots. Every prompt follows Veo 3.1's five-part structure:

```
[Cinematography] + [Subject] + [Action] + [Context] + [Style & Ambiance]
```

**Do not open this file until the asset manifest in `04-image-prompts.md` §6 is fully ticked.**

---

## How to read each shot block

| Field | Meaning |
|---|---|
| **Mode** | Which Flow generation mode: *Ingredients to Video*, *Frames to Video*, or *Text to Video* |
| **Ingredients** | Exactly which images to drag in. **Never more than 3.** |
| **Frames** | Start / end frame if using Frames to Video |
| **Length** | Set the Generation length control to this |
| **Prompt** | Paste verbatim. Identity and environment strings inside are locked — do not paraphrase. |
| **Accept if** | What a good generation looks like |
| **Reject if** | Immediate bin, regenerate |

### Universal settings for every generation

```
Model:        Veo 3.1  (Veo 3.1 Fast is acceptable for test passes only, never for final)
Aspect ratio: 16:9
Resolution:   1080p
Outputs:      2 per prompt (compare, keep the better)
```

### Universal exclusions — already appended to every prompt below

```
No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
signage, no numbers, no logos, no readable writing on clothing or equipment. No user
interface on any screen. No workers without hard hats or hi-vis vests. No slow-motion, no
lens flare stars, no colour saturation boost, no cinematic letterbox bars.
```

**Why "no dialogue" is on every single prompt:** the film carries voiceover in two languages recorded in post. If Veo generates mouth movement or speech, the AR and EN cuts will both look dubbed. This exclusion is what makes one visual render serve both languages — it is the most important line in this document.

**Why "no workers without hard hats":** your audience is site directors. One bare head in the background and the film loses them.

---

# ACT 1 — THE LAND

## Shot 01 — Aerial, dawn oasis

**Mode:** Ingredients to Video · **Ingredients:** `LOC-DESERT.png`, `STYLE.png` · **Length:** 8s

> **Consider a real drone plate instead.** This is the first frame the audience sees, and Al-Ahsa is a real, distinctive, UNESCO-listed place that Veo will only approximate. If you can source 8 seconds of genuine aerial footage of the Al Hofuf oasis edge, use it and skip this generation. The credibility return is high.

```
Slow high aerial shot drifting forward, camera gliding steadily at altitude with a gentle
downward tilt.

An empty landscape with no people and no vehicles.

The camera drifts slowly forward over the palm groves, the road unspooling beneath and ahead
toward the horizon, thin ground haze parting.

The Al-Ahsa oasis edge in Eastern Saudi Arabia at first light: a two-lane asphalt road
running straight through low apricot-coloured sand dunes, dense date-palm groves in the
middle distance, a pale blue-grey pre-sunrise sky with a thin band of warm gold on the
horizon, faint ground haze.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Cool pre-dawn light, deep amber and dust-ochre midtones in the sand, cool steel-grey
shadows, gentle film grain, subtle halation on the horizon, restrained editorial grade.
Ambient noise: a low steady desert wind, distant birdsong from the palm groves, no music.

No dialogue, no speech, no voices. No on-screen text, no lettering, no signage, no logos. No
people, no camels, no vehicles. No slow-motion, no colour saturation boost, no cinematic
letterbox bars.
```

**Accept if:** date palms are clearly present and the light is genuinely pre-dawn cool.
**Reject if:** empty Rub' al Khali-style dunes with no vegetation (geographically wrong for Al Hofuf), sunset warmth instead of dawn cool, any camel.

---

## Shot 02 — Ground level, sunrise

**Mode:** Ingredients to Video · **Ingredients:** `LOC-DESERT.png`, `STYLE.png` · **Length:** 8s

```
Low static shot, camera at road-surface height, wide anamorphic lens, deep focus.

An empty two-lane asphalt road and a single distant white pickup truck.

The camera holds completely still as the sun's first edge breaks the horizon; far in the
distance a single white pickup truck crosses the frame from left to right, small and
unremarkable.

The Al-Ahsa oasis edge in Eastern Saudi Arabia at first light: a two-lane asphalt road
running straight through low apricot-coloured sand dunes, dense date-palm groves in the
middle distance, a pale blue-grey pre-sunrise sky with a thin band of warm gold on the
horizon, faint ground haze.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Warm low sun flaring gently along the road surface, deep amber and dust-ochre midtones, cool
steel-grey shadows, gentle film grain, subtle halation, restrained editorial grade.
Ambient noise: low desert wind, the faint distant hum of a single engine passing, no music.

No dialogue, no speech, no voices. No on-screen text, no lettering, no signage, no logos. No
people visible, no camels. No slow-motion, no lens flare stars, no colour saturation boost.
```

**Accept if:** the camera is genuinely static and the truck reads as incidental, not as a hero vehicle.
**Reject if:** camera moves, truck is large or centred, any branding visible on the truck.

---

# ACT 2 — THE PROBLEM

## Shot 03 — Faisal in his office

**Mode:** Frames to Video · **Start frame:** `FRAME-03-START.png` · **Ingredients:** `CHAR-FAISAL.png`, `STYLE.png` · **Length:** 8s

```
Medium shot, static camera on sticks, 50mm lens, shallow depth of field.

Faisal, a 45-year-old Saudi man, olive-brown skin, short greying black hair, short
salt-and-pepper beard, sharp intelligent eyes with faint lines, solid build, 180cm tall. He
wears a crisp white thobe with a folded white ghutra headscarf, and no site vest and no hard
hat.

He stands over the drawing, studies it, then turns his left wrist and checks his watch. He
holds still for a moment, then lifts his head and looks off toward the window, jaw set. A
contained, calculating concern. He does not speak.

A functional site project office in Saudi Arabia: a large printed construction drawing spread
across a laminate desk, a desktop monitor turned away from camera, a hard hat resting on a
filing cabinet, aluminium-framed window with dusty vertical blinds half open casting hard
slatted light, plain off-white walls, a wall-mounted programme chart.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Warm interior daylight, hard slatted blind shadows across him, cool steel-grey shadows,
gentle film grain, restrained editorial grade.
Ambient noise: the low hum of an air conditioner, a distant muffled construction site
outside, faint paper movement, no music.

No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
readable drawings or charts, no logos. No user interface on any screen, monitor stays dark.
No black agal cord on the headscarf. No slow-motion, no colour saturation boost.
```

**Accept if:** the watch-check reads clearly, his face matches `CHAR-FAISAL.png`, blind light is hard and graphic.
**Reject if:** his mouth moves, he wears a hard hat or vest, an agal appears on the ghutra, the drawing has legible linework that looks like fake CAD.

---

## Shot 04 — The empty site

**Mode:** Frames to Video · **Start frame:** `FRAME-04-START.png` · **Ingredients:** `CHAR-FAISAL.png`, `LOC-SITE.png`, `STYLE.png` · **Length:** 8s

> The single most important direction on this shot: **the site must look empty.** Veo's instinct is to fill a construction site with activity. Every generation that adds workers is a failed generation, no matter how good it looks.

```
Wide shot with a very slow push in, wide-angle lens, deep focus, subtle handheld weight.

Faisal, a 45-year-old Saudi man, olive-brown skin, short greying black hair, short
salt-and-pepper beard, solid build, 180cm tall, wearing a crisp white thobe with a folded
white ghutra headscarf, and over it a plain grey hi-vis site vest and a grey hard hat. He is
small in the frame at the lower left, seen from behind and to the side.

He walks a few slow steps forward into the wide frame, then stops and stands still, looking
up at the structure. The camera pushes in very slowly. Nothing else in the frame moves except
drifting dust.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf, Saudi Arabia:
exposed concrete columns and floor slabs four storeys up, steel scaffolding with green safety
mesh standing idle and unused, rebar bundles and stacked concrete blocks on graded sand
ground, a stationary yellow mobile crane, low date palms and flat sand-coloured buildings
beyond the site fence, mid-morning high hard sunlight, short shadows, fine airborne dust. The
site is conspicuously quiet and under-staffed, with only two distant workers heavily out of
focus, faces not visible.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Hard Gulf midday sunlight, deep amber and dust-ochre midtones, cool steel-grey shadows,
gentle film grain, restrained editorial grade.
Ambient noise: wind across open ground, a single distant metallic clang, no machinery running,
conspicuous quiet, no music.

No dialogue, no speech, no voices. No on-screen text, no lettering, no signage, no logos. No
crowds, no busy activity, no moving machinery, no more than two distant workers. No workers
without hard hats or hi-vis vests. No black agal cord. No slow-motion, no colour saturation
boost.
```

**Accept if:** the frame feels stalled and quiet; the crane is motionless.
**Reject if:** more than two background workers, any machinery in motion, the site reads as busy.

---

## Shot 05 — The call is made

**Mode:** Ingredients to Video · **Ingredients:** `CHAR-FAISAL.png`, `LOC-OFFICE-CLIENT.png`, `STYLE.png` · **Length:** 6s

```
Extreme close-up insert, 100mm macro lens, very shallow depth of field, static.

A man's hand and forearm in a crisp white thobe sleeve.

The hand reaches into frame and lifts a black desk phone handset from its cradle in one
unhurried, decisive movement, then holds still.

A functional site project office in Saudi Arabia: the corner of a laminate desk, a black desk
phone, the edge of a large printed construction drawing, hard slatted light from dusty
vertical blinds falling across the desk surface, plain off-white wall behind, softly out of
focus.

Documentary-industrial cinematography, shot on ARRI Alexa. Warm interior daylight, hard
slatted shadows, cool grey falloff, gentle film grain, restrained editorial grade.
Ambient noise: the mechanical click of a phone handset lifting, a soft dial tone, air
conditioner hum, no music.

No dialogue, no speech, no voices. No face in frame. No on-screen text, no lettering, no
readable buttons or labels on the phone, no logos. No phone screen visible. No slow-motion,
no colour saturation boost.
```

**Accept if:** the movement is decisive and the hand looks like a real 45-year-old man's hand.
**Reject if:** extra or malformed fingers, mobile phone with a visible screen, any readable text on the handset.

---

# ACT 3 — THE PROCESS

## Shot 06 — Khalid takes the call

**Mode:** Frames to Video · **Start frame:** `FRAME-06-START.png` · **Ingredients:** `CHAR-KHALID.png`, `STYLE.png` · **Length:** 8s

> This is the tonal pivot of the whole film. The audience must feel the problem land with someone competent. Khalid is already writing before the call ends — that is the entire performance note.

> **SPIKE SHOT 1 of 3.** Generate this as part of the consistency spike in `07-consistency-qa.md` §1 before any other character shot. Do not proceed to full production until it passes.

```
Medium shot, static camera, 50mm lens, shallow depth of field.

Khalid, a 38-year-old Saudi man, medium-brown skin, short black hair, full neatly trimmed
black beard, calm steady dark brown eyes, strong straight nose, lean athletic build, 178cm
tall. He wears a white ASMC hi-vis safety vest with reflective silver bands over a grey
long-sleeve work shirt, a clean white hard hat, and clear safety glasses pushed up on the
brim. A black lanyard with a plain ID card hangs at his chest.

He listens with a phone to his right ear, already writing in the open notebook with his left
hand. He nods once, short and decisive, then glances up and away from the desk, thinking
ahead. He does not speak.

The ASMC operations room in Al Hofuf: a clean modern office with a long desk, three large
dark monitors, a wall-mounted map of Saudi Arabia with no readable labels, neat stacks of
document folders, a black desk phone, warm cream walls and near-black joinery, soft daylight
from a window on the left.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Soft warm interior daylight from the left, cool steel-grey shadows, gentle film grain,
restrained editorial grade.
Ambient noise: quiet office room tone, a pen moving on paper, the faint tick of a wall clock,
no music.

No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
readable map labels, no logos, no writing on the vest or hard hat. All monitors dark and
switched off with no user interface visible. No slow-motion, no colour saturation boost.
```

**Accept if:** the single decisive nod is legible; face matches `CHAR-KHALID.png`; all three monitors are dark.
**Reject if:** his mouth moves, any monitor shows a UI or glow with visible content, hard hat is not white.

---

## Shot 07 — Compliance, in hands

**Mode:** Frames to Video · **Start frame:** `FRAME-07-START.png` · **Ingredients:** `LOC-OPS.png`, `STYLE.png` · **Length:** 8s

> This shot carries the compliance claim — Iqama, medical clearance, insurance — with no text and no narration cue other than the VO. It works entirely through the deliberateness of the hands. Three folders, three checks. Do not let it become four.

```
Extreme close-up insert, high angle looking straight down at the desk, 50mm lens, shallow
depth of field, static.

Only a man's hands and forearms in grey long-sleeve work shirt cuffs.

The hands slide the three plain folders apart into a neat row, open the nearest one, turn a
single page, then press a rubber stamp down firmly onto it and lift it away. Deliberate,
unhurried, methodical.

The ASMC operations room in Al Hofuf: a warm cream desk surface, neat stacks of document
folders at the edge of frame, soft daylight from the left, near-black joinery just visible
beyond.

Documentary-industrial cinematography, shot on ARRI Alexa. Soft warm interior light, sharp on
the hands and the nearest folder, cool grey falloff, gentle film grain, restrained editorial
grade.
Ambient noise: paper sliding on wood, a page turning, the solid thud of a rubber stamp, quiet
room tone, no music.

No dialogue, no speech, no voices. No face in frame. All documents blank and blurred with no
readable text, no printing, no lettering, no stamps with legible marks, no logos. No slow-
motion, no colour saturation boost.
```

**Accept if:** exactly three folders, hands are anatomically clean, the stamp beat is clear and satisfying.
**Reject if:** legible text appears on any document, more or fewer than three folders, malformed hands.

---

## Shot 08 — Decision becomes movement

**Mode:** Ingredients to Video · **Ingredients:** *saved frame from approved Shot 06*, `LOC-OPS.png`, `STYLE.png` · **Length:** 6s

> **Use Flow's "Save frame" on your approved Shot 06** and use that saved frame here in place of `CHAR-KHALID.png`. Chaining to real generated pixels from a shot you already approved is materially more reliable than re-deriving from the studio plate, and both shots are in the same room with the same light.

```
Medium shot, static camera, 50mm lens.

Khalid, a 38-year-old Saudi man, medium-brown skin, short black hair, full neatly trimmed
black beard, calm steady dark brown eyes, lean athletic build, 178cm tall. He wears a white
ASMC hi-vis safety vest with reflective silver bands over a grey long-sleeve work shirt, and
clear safety glasses pushed up on the brim of a clean white hard hat. A black lanyard with a
plain ID card hangs at his chest.

He sets the phone down, stands up from the desk, picks up the white hard hat and settles it
onto his head with one hand, and walks out of frame to the right. The camera does not follow.

The ASMC operations room in Al Hofuf: a clean modern office with a long desk, three large
dark monitors, a wall-mounted map of Saudi Arabia with no readable labels, neat stacks of
document folders, warm cream walls and near-black joinery, soft daylight from a window on the
left.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Soft warm interior daylight, cool steel-grey shadows, gentle film grain, restrained editorial
grade.
Ambient noise: a chair shifting, footsteps on hard floor moving away, a door handle, quiet
room tone, no music.

No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
readable map labels, no logos, no writing on the vest or hard hat. All monitors dark and
switched off with no user interface visible. No slow-motion, no colour saturation boost.
```

**Accept if:** he fully clears frame before the clip ends — you need that for a clean cut.
**Reject if:** he lingers in frame, the hard hat changes colour, the room's light direction has flipped from Shot 06.

---

# ACT 4 — THE WORK

## Shot 09 — The weld

**Mode:** Frames to Video · **Start frame:** `FRAME-09-START.png` · **Ingredients:** `CHAR-RAVI.png`, `LOC-WORKSHOP.png`, `STYLE.png` · **Length:** 8s

> This should be the most beautiful shot in the film. It is where the word "skilled" stops being a claim and becomes visible. Spend generations here — five or six attempts is reasonable.

> **SPIKE SHOT 2 of 3.** Generate this as part of the consistency spike in `07-consistency-qa.md` §1. Tests whether Ravi's face holds under the hardest lighting in the film.

```
Close-up, slow slight dolly right, 50mm lens, very shallow depth of field, high contrast.

Ravi, a 32-year-old South Asian man, warm brown skin, short black hair, clean-shaven with a
faint moustache shadow, focused dark eyes, sturdy compact build, 172cm tall. He wears
navy-blue ASMC work coveralls with reflective bands at the sleeves, an orange hard hat, heavy
scuffed leather work gloves, and steel-toe boots, with his welding helmet lowered down over
his face.

He strikes an arc and runs a steady weld bead along the clamped steel plate; brilliant white
sparks fountain and scatter across the dark bench. He finishes, releases the torch, flips the
welding helmet up onto his forehead, leans in close to inspect the bead he has just laid, and
gives a single small satisfied nod.

A steel fabrication workshop bay: a heavy steel workbench with clamped steel plate, a large
roller shutter door half open at the rear letting a hard shaft of dusty sunlight across the
floor, coiled welding cable, gas cylinders chained upright to the wall, dark soot-marked
concrete floor, tool boards in shadow.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Very high contrast, the weld arc as the dominant light source blowing out to pure white, hard
shafted sunlight behind, deep steel-grey shadows, gentle film grain, strong subtle halation
around the arc, restrained editorial grade.
Ambient noise: the crackling buzz of an arc welder, sparks pattering on concrete, the hiss of
shielding gas, a distant workshop radio too faint to make out words, no music.

No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
logos, no writing on the coveralls or hard hat. No bare eyes exposed to the arc, helmet must
be down while welding. No slow-motion, no colour saturation boost.
```

**Accept if:** the helmet is genuinely **down** during the arc and **up** for the inspection; sparks behave physically; the nod is small.
**Reject if:** he welds with the helmet up or with bare eyes (an unforgivable safety error for this audience), gloves are missing, sparks look like particle-effect confetti.

---

## Shot 10 — Ravi on the live site

**Mode:** Ingredients to Video · **Ingredients:** `CHAR-RAVI.png`, `LOC-SITE.png`, `STYLE.png` · **Length:** 8s

> No start frame — this shot needs motion freedom to get a natural walk. The direct visual rhyme against Shot 04 is the argument of the entire film: same site, transformed.

```
Medium tracking shot moving laterally alongside the subject at chest height, 35mm lens,
subtle handheld weight, shallow depth of field.

Ravi, a 32-year-old South Asian man, warm brown skin, short black hair, clean-shaven with a
faint moustache shadow, focused dark eyes, sturdy compact build, 172cm tall. He wears
navy-blue ASMC work coveralls with reflective bands at the sleeves, an orange hard hat, heavy
scuffed leather work gloves, and steel-toe boots, welding helmet tipped up on his forehead.

He walks purposefully through the site carrying a coil of heavy cable over one shoulder,
looking ahead, not at the camera. The camera tracks alongside him. Anonymous workers pass
through the background.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf, Saudi Arabia:
exposed concrete columns and floor slabs four storeys up, steel scaffolding with green safety
mesh, rebar bundles and stacked concrete blocks on graded sand ground, a yellow mobile crane,
low date palms and flat sand-coloured buildings beyond the site fence, late morning strong
directional sunlight, fine airborne dust. The site is busy and active, with anonymous
background workers in hard hats and hi-vis vests, heavily out of focus, faces not visible.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Hard Gulf sunlight, deep amber and dust-ochre midtones, cool steel-grey shadows, gentle film
grain, restrained editorial grade.
Ambient noise: boots on gravel, distant hammering and power tools, a reversing beeper far off,
wind, no music.

No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
signage, no logos, no writing on the coveralls or hard hat. No background faces in focus. No
workers without hard hats or hi-vis vests. No looking at camera. No slow-motion, no colour
saturation boost.
```

**Accept if:** the site reads busy, every background worker has a hard hat, he never looks at lens.
**Reject if:** any bare-headed worker, a background face resolves sharply, his face has drifted from Shot 09.

---

## Shot 11 — The crane

**Mode:** Ingredients to Video · **Ingredients:** `LOC-SITE.png`, `STYLE.png` · **Length:** 8s

```
Low wide angle looking steeply up, 24mm wide-angle lens, deep focus, slow tilt following the
boom.

A yellow mobile crane and a suspended bundle of steel rebar.

The crane hoists the rebar bundle upward and swings the boom slowly across frame against the
sky; the bundle rotates gently on its slings. The camera tilts slowly to follow. No people in
focus.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf, Saudi Arabia: seen
from directly below, exposed concrete columns and floor slabs rising at the frame edge, steel
scaffolding with green safety mesh, a hard cloudless pale blue Gulf sky, fine airborne dust
catching the light, late morning strong directional sunlight.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Hard directional sunlight, deep amber and dust-ochre midtones on the steel, cool steel-grey
shadows, gentle film grain, subtle halation at the sky edge, restrained editorial grade.
Ambient noise: the deep diesel note of a crane engine under load, steel slings creaking, a
slow rhythmic reversing beeper, wind, no music.

No dialogue, no speech, no voices. No on-screen text, no lettering, no signage, no logos, no
brand names or model numbers on the crane. No people in focus. No slow-motion, no lens flare
stars, no colour saturation boost.
```

**Accept if:** the load moves with believable weight and inertia; the crane is unbranded.
**Reject if:** any manufacturer name or model number is legible on the crane, the load moves weightlessly, load swings unsafely over people.

---

## Shot 12 — Supervision

**Mode:** Frames to Video · **Start frame:** `FRAME-12-START.png` · **Ingredients:** `PAIR-KHALID-RAVI.png`, `LOC-SITE.png`, `STYLE.png` · **Length:** 8s

> Note the direction of the exchange: Khalid **points and then Ravi confirms**. Khalid is consulting his tradesman, not issuing orders. This is a deliberate choice about how ASMC's workforce culture reads on screen, and it costs nothing.

```
Two-shot at chest height, static with subtle handheld weight, 50mm lens, shallow depth of
field.

Two men standing at the open edge of a concrete floor slab, in profile facing each other,
three-quarter to camera. On the left: Khalid, a 38-year-old Saudi man, medium-brown skin,
full neatly trimmed black beard, lean athletic build, in a white ASMC hi-vis safety vest with
reflective silver bands over a grey long-sleeve work shirt, a clean white hard hat, clear
safety glasses pushed up on the brim, holding a clipboard. On the right: Ravi, a 32-year-old
South Asian man, warm brown skin, clean-shaven with a faint moustache shadow, sturdy compact
build, in navy-blue ASMC work coveralls with reflective bands at the sleeves, an orange hard
hat, heavy scuffed leather work gloves, welding helmet tipped up on his forehead.

Khalid raises his hand and points up at something above and behind Ravi. Ravi turns his head
to follow the line, looks, and nods twice in agreement. Khalid looks down and marks the
clipboard. Neither man speaks.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf: steel scaffolding
with green safety mesh, exposed concrete columns, a yellow mobile crane and low date palms far
below and beyond, late morning strong directional sunlight, fine airborne dust. Anonymous
background workers in hard hats and hi-vis vests, heavily out of focus, faces not visible.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Hard Gulf sunlight, deep amber and dust-ochre midtones, cool steel-grey shadows, gentle film
grain, restrained editorial grade.
Ambient noise: wind at height, distant power tools and hammering, the scratch of a pen on
paper, no music.

No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
signage, no logos, no readable writing on the clipboard or clothing. No background faces in
focus. No workers without hard hats or hi-vis vests. No slow-motion, no colour saturation
boost.
```

**Accept if:** both faces match their plates, the point-then-nod exchange is legible without sound, no lip movement.
**Reject if:** either man's mouth moves, hard hat colours swap, the clipboard shows legible writing.

---

# ACT 5 — THE OUTCOME

## Shot 13 — Golden hour reveal

**Mode:** Frames to Video · **Start frame:** `FRAME-13-START.png` · **End frame:** `FRAME-13-END.png` · **Ingredients:** `STYLE.png` · **Length:** 8s

> Frames to Video is used here specifically because a crane-up reveal needs to *arrive* somewhere. Given only a start frame, Veo will drift upward vaguely. Given both, it will build the move.

```
Crane shot rising smoothly from ground level to high above rooftop height, 35mm lens, deep
focus, steady mechanical rise.

A fully active construction site at golden hour.

The camera rises steadily from the sand at the base of the scaffold, climbing past the floor
slabs to reveal the whole site working on multiple levels, the crane mid-lift, long shadows
stretching across the ground.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf, Saudi Arabia:
exposed concrete columns and floor slabs four storeys up, steel scaffolding with green safety
mesh, rebar bundles and stacked concrete blocks on graded sand ground, a yellow mobile crane,
low date palms and flat sand-coloured buildings beyond the site fence, late afternoon golden
hour, very long warm shadows, low amber sun, fine airborne dust. All visible workers wear hard
hats and hi-vis vests, anonymous and out of focus, faces not visible.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes. Warm
golden hour light, deep amber and dust-ochre midtones, cool steel-grey shadows, gentle film
grain, subtle halation on the low sun, restrained editorial grade.
Ambient noise: layered site activity, hammering, a crane engine, distant voices too far to make
out words, wind, no music.

No dialogue, no speech, no voices, no intelligible words. No on-screen text, no lettering, no
signage, no logos. No faces in focus. No workers without hard hats or hi-vis vests. No slow-
motion, no lens flare stars, no colour saturation boost.
```

**Accept if:** the rise is smooth and mechanical (not floaty), and the end frame composition is genuinely reached.
**Reject if:** the move drifts sideways, the site is under-populated, any bare head.

---

## Shot 14 — The handover

**Mode:** Frames to Video · **Start frame:** `FRAME-14-START.png` · **End frame:** `FRAME-14-END.png` · **Ingredients:** `PAIR-KHALID-FAISAL.png`, `STYLE.png` · **Length:** 8s

> Both frames are specified because the handshake must actually happen and must be framed properly. Left to a text prompt alone, Veo produces vague hand-hovering roughly half the time.

> **SPIKE SHOT 3 of 3.** Generate this as part of the consistency spike in `07-consistency-qa.md` §1. Tests whether a pair plate holds two faces, whether Frames-to-Video delivers the handshake, and whether Khalid still matches his Shot 06 self across totally different light.

```
Two-shot from the side at chest height, slow lateral tracking alongside the subjects, 50mm
lens, shallow depth of field.

Two men walking side by side across the site in profile. On the right: Khalid, a 38-year-old
Saudi man, medium-brown skin, full neatly trimmed black beard, lean athletic build, in a white
ASMC hi-vis safety vest with reflective silver bands over a grey long-sleeve work shirt, a
clean white hard hat, clear safety glasses pushed up on the brim. On the left: Faisal, a
45-year-old Saudi man, olive-brown skin, short greying black hair, short salt-and-pepper
beard, solid build, in a crisp white thobe with a folded white ghutra headscarf, and over it a
plain grey hi-vis site vest and a grey hard hat.

They walk unhurried together; Faisal raises his right hand and gestures up at the structure,
Khalid follows the gesture and nods. They slow, stop, turn to face each other, and shake hands
once — brief and professional. Measured expressions, no grinning. Neither man speaks.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf: exposed concrete
columns and floor slabs, steel scaffolding with green safety mesh, a yellow mobile crane,
graded sand ground, late afternoon golden hour, long warm shadows, low amber sun flaring behind
them, fine airborne dust. Anonymous background workers in hard hats and hi-vis vests, heavily
out of focus, faces not visible.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes. Warm
golden hour backlight, deep amber and dust-ochre midtones, cool steel-grey shadows, gentle film
grain, subtle halation, restrained editorial grade.
Ambient noise: boots on gravel, distant site activity winding down, wind, no music.

No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
signage, no logos, no readable writing on clothing. No grinning, no laughing, no celebration,
no back-slapping. No black agal cord. No background faces in focus. No slow-motion, no lens
flare stars, no colour saturation boost.
```

**Accept if:** the handshake is brief and professional, both faces hold, Faisal's thobe-under-vest silhouette is correct.
**Reject if:** the handshake is held too long or becomes a hug, either man grins broadly, an agal appears, hands are malformed.

---

## Shot 15 — The three of them

**Mode:** Frames to Video · **Start frame:** `FRAME-15-START.png` · **Ingredients:** `TRIO-ALL.png`, `STYLE.png` · **Length:** 8s

> They face **away** from camera. Nobody looks at the lens, nobody poses, nobody smiles at us. This is the difference between an ending that feels earned and one that feels like a stock photo. The stat row and end card resolve over this shot, so leave the upper third of the frame relatively clean.

```
Wide static shot, 35mm lens, deep focus, camera locked off.

Three men standing in a loose line in the mid-ground, backs three-quarter to camera, looking
up and away from the lens at the completed upper floors. Left, in a white ASMC hi-vis safety
vest with reflective silver bands and a clean white hard hat: Khalid, a 38-year-old Saudi man,
lean athletic build. Centre, in a crisp white thobe with a folded white ghutra headscarf under
a plain grey hi-vis site vest and a grey hard hat: Faisal, a 45-year-old Saudi man, solid
build. Right, in navy-blue ASMC work coveralls with reflective bands and an orange hard hat:
Ravi, a 32-year-old South Asian man, sturdy compact build.

They stand still and look up at the structure. Faisal shifts his weight slightly. Ravi tilts
his head back a little further. Dust drifts through the low sun behind them. Nobody turns
toward the camera and nobody speaks.

A mid-rise reinforced-concrete construction site on the edge of Al Hofuf, Saudi Arabia:
exposed concrete columns and completed floor slabs four storeys up, steel scaffolding with
green safety mesh, a yellow mobile crane, low date palms and flat sand-coloured buildings
beyond the site fence, late afternoon golden hour, very long warm shadows stretching toward
camera, low amber sun behind them, fine airborne dust.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes. Warm
golden hour backlight, deep amber and dust-ochre midtones, cool steel-grey shadows, gentle
film grain, subtle halation on the low sun, restrained editorial grade.
Ambient noise: wind, a distant site winding down for the day, no music.

No dialogue, no speech, no voices, no lip movement. No on-screen text, no lettering, no
signage, no logos, no readable writing on clothing. Nobody looks at the camera, no posing, no
smiling at camera, no folded arms hero pose. No black agal cord. No slow-motion, no lens flare
stars, no colour saturation boost.
```

**Accept if:** nobody faces camera, the three hard-hat colours read clearly at distance, the upper third is clean enough for supers.
**Reject if:** anyone turns to camera, it reads as a posed team photo, hard hat colours have swapped.

---

## Generation log — fill this in as you go

| Shot | Attempts | Chosen gen | Frame saved for chaining | Notes |
|---|---|---|---|---|
| 01 | | | — | |
| 02 | | | — | |
| 03 | | | | |
| 04 | | | | |
| 05 | | | — | |
| 06 | | | **required → Shot 08** | |
| 07 | | | — | |
| 08 | | | — | |
| 09 | | | | |
| 10 | | | | |
| 11 | | | — | |
| 12 | | | | |
| 13 | | | — | |
| 14 | | | | |
| 15 | | | — | |

---

## Where `Extend` is permitted

**Shot 01 only.** If you want the opening aerial to run longer than 8s for a slower, more confident open, Extend it — there are no faces to drift and the audio bed is a continuous wind tone, which is exactly what Extend handles well.

**Nowhere else.** Extend regenerates from the final second of the previous clip only. On any shot containing a character it will progressively degrade the face, and because each extension inherits the previous drift, the degradation compounds. If a character shot needs to be longer, generate a new 8s shot with the same ingredients and cut between them.

---

## Generation order

Do not generate top to bottom. Some shots depend on saved frames from earlier shots, and the consistency spike must pass before any other character shot is attempted. Follow this sequence.

### Phase 0 — Prerequisites (before any video generation)

1. Complete every image in `04-image-prompts.md` §6 — all 26 assets ticked.
2. All three `CHAR-*.png` plates have passed the head-turn test (`04-image-prompts.md` §2).
3. Read `07-consistency-qa.md` §1 in full.

### Phase 1 — Consistency spike (3 shots, 1 output each)

| Order | Shot | Why first |
|---|---|---|
| 1 | **Shot 06** | Khalid in flat interior light. Cheapest face test. If this fails, regenerating `CHAR-KHALID.png` fixes it before you've spent on harder shots. |
| 2 | **Shot 09** | Ravi under extreme contrast (welding arc). The hardest lighting in the film — if Ravi holds here, he holds everywhere. |
| 3 | **Shot 14** | Khalid + Faisal pair plate, golden hour, handshake. Tests pair-plate face holding, Frames-to-Video handshake delivery, and cross-light Khalid consistency against Shot 06. |

**Do not proceed to Phase 2 until all ten spike pass criteria in `07-consistency-qa.md` §1 are met.** Iterating three shots is cheap; iterating fifteen is not.

### Phase 2 — Landscape and insert shots (no character faces, 5 shots)

These have no face-drift risk and can be generated in any order. Take the second acceptable generation and move on.

| Shot | Notes |
|---|---|
| 01 | Aerial. Consider a real drone plate instead (see shot note). |
| 02 | Ground level, static. Quick. |
| 05 | Phone handset insert. No face. Watch for malformed fingers. |
| 07 | Document folders insert. No face. Watch for legible text. |
| 11 | Crane, low angle. No faces in focus. Watch for crane branding. |

### Phase 3 — Character shots with no saved-frame dependency (4 shots)

| Order | Shot | Depends on |
|---|---|---|
| 4 | **Shot 03** | `CHAR-FAISAL.png` only |
| 5 | **Shot 04** | `CHAR-FAISAL.png` + `LOC-SITE.png` |
| 6 | **Shot 10** | `CHAR-RAVI.png` + `LOC-SITE.png` — compare directly against approved Shot 04 (same site, now busy) |
| 7 | **Shot 12** | `PAIR-KHALID-RAVI.png` — for best results, use a saved frame from approved Shot 06 as the Khalid reference instead of the studio plate (see Saved-frame chain below) |

### Phase 4 — Saved-frame chain shots (3 shots)

These shots explicitly call for a saved frame from an already-approved clip. Generate them only after their dependency is approved.

| Order | Shot | Saved-frame dependency |
|---|---|---|
| 8 | **Shot 08** | Save a frame from approved **Shot 06** (same room, same light). Use it in place of `CHAR-KHALID.png`. |
| 9 | **Shot 13** | No character plate needed (wide, faces out of focus), but benefits from the approved `LOC-SITE.png` look. Generate after Shot 10 so the site reads as the same location. |
| 10 | **Shot 15** | `TRIO-ALL.png` — for best results, use a saved frame from approved **Shot 12** as the Khalid reference and approved **Shot 10** as the Ravi reference. Both are in the same golden-hour site light. |

### Summary dependency graph

```
Spike:  06 ──┐
         09 ─┤── all three must pass before Phase 2-4
         14 ─┘

Chain:  06 (approved) → save frame → 08
        06 (approved) → save frame → 12 → save frame → 15
        10 (approved) → save frame → 15

Free:   01, 02, 03, 04, 05, 07, 11, 13
```

---

## Saved-frame chain

Flow's **Save frame** tool extracts a still from a generated clip. A frame from an approved clip is a *better* character reference than the original studio plate for subsequent shots, because it already carries the film's grade, lighting behaviour and grain. Build the chain forward — always anchor a new shot to the most recently approved appearance **in similar light**.

### The full chain for this film

```
CHAR-KHALID.png
  → Shot 06 (approved, interior/soft light)
      → save frame → Shot 08  (same room, same light)
      → save frame → Shot 12  (site, hard midday — different light, accept some drift)
          → save frame → Shot 14  (golden hour — different light again)
          → save frame → Shot 15  (golden hour, wide, faces small)

CHAR-RAVI.png
  → Shot 09 (approved, workshop/extreme contrast)
      → save frame → Shot 10  (site, hard midday — different light)
          → save frame → Shot 12  (site, pair shot)
          → save frame → Shot 15  (golden hour, wide, faces small)

CHAR-FAISAL.png
  → Shot 03 (approved, office/soft light)
      → save frame → Shot 04  (site, midday — different light)
          → save frame → Shot 14  (golden hour, pair shot)
          → save frame → Shot 15  (golden hour, wide, faces small)
```

### Rules for the chain

1. **Never chain across a light change without accepting some drift.** Interior → golden hour will drift more than interior → interior. If the drift is unacceptable, fall back to the studio plate for that shot, or add a Nano Banana start frame (drift triage level 5 in `07-consistency-qa.md` §3).
2. **Always save a frame from the *approved* clip, not a rejected one.** A frame from a clip you didn't approve carries the wrong face.
3. **Save the frame at the point in the clip where the face is clearest and most front-on** — usually 1–2 seconds in, after any initial motion settles.
4. **Name saved frames by shot and character**: `SAVED-06-KHALID.png`, `SAVED-12-RAVI.png`, etc. Never reuse a saved frame name across shots.
5. **Update the generation log** with the saved-frame name in the "Frame saved for chaining" column.

---

## Shot difficulty tiers

From `07-consistency-qa.md` §4. Use this to set expectations and allocate attempts before you start.

| Tier | Shots | Expected attempts | Why |
|---|---|---|---|
| **Simple** | 01, 02, 05, 07, 11 | ~2 each | No character faces. Landscape, inserts, equipment. Take the second acceptable generation and move on. |
| **Character** | 03, 04, 06, 08, 10 | ~3 each | Single character, face must hold but lighting is manageable. |
| **Hard** | 09, 12, 13, 14, 15 | ~4 each | Extreme contrast, pair plates, multi-character, or complex camera moves. Shot 09 may need 6. |

**Total video budget:** ~61 generations (including spike re-runs and contingency). See `07-consistency-qa.md` §4 for the full breakdown.

### Where to spend and where not to

**Spend extra attempts on:**
- **Shot 09** (the weld) — the hero shot, the "skilled" claim made visible
- **Shot 14** (the handshake) — the relationship moment, pair-plate face holding
- **Shot 15** (the trio) — the final image, three faces at distance, composition must leave the upper third clean for supers

**Take the second acceptable generation and move on:**
- **Shot 02** — establishing, no characters, structurally just needs to exist
- **Shot 05** — phone insert, no face, 5 seconds in the cut
- **Shot 11** — crane, no faces in focus, equipment pillar only

---

## Cutdown relevance map

From `03-script-shotlist.md`. No shot is generated twice — every cutdown is derived from the same picture lock. But knowing which shots survive into which cutdown tells you where to spend and where to accept "good enough."

| Shot | 105s master | 60s cut | 30s cut | 15s vertical A (Mobilisation) | 15s vertical B (Compliance) | 15s vertical C (Equipment) |
|---|---|---|---|---|---|---|
| 01 | ✓ | ✓ | — | — | — | — |
| 02 | ✓ | — | — | — | — | — |
| 03 | ✓ | ✓ | — | — | — | — |
| 04 | ✓ | ✓ | ✓ | ✓ | — | — |
| 05 | ✓ | ✓ | ✓ | ✓ | — | — |
| 06 | ✓ | ✓ | — | — | — | — |
| 07 | ✓ | ✓ | ✓ | — | ✓ | — |
| 08 | ✓ | — | — | — | — | — |
| 09 | ✓ | ✓ | — | — | — | ✓ |
| 10 | ✓ | — | — | — | — | — |
| 11 | ✓ | ✓ | — | — | — | ✓ |
| 12 | ✓ | ✓ | — | — | ✓ | — |
| 13 | ✓ | ✓ | ✓ | ✓ | — | — |
| 14 | ✓ | ✓ | ✓ | — | — | — |
| 15 | ✓ | ✓ | — | — | — | — |
| EC | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |

### How to read this

- **Shots in every cutdown** (04, 05, 13, 14, EC): these carry the film across all deliverables. Spend the most here.
- **Shots only in the 105s master** (02, 08, 10): these give the long cut its rhythm but don't appear in any shorter version. Accept "good enough" faster.
- **Vertical-specific shots**: Shot 04 is the mobilisation pillar; Shot 07 is the compliance pillar; Shots 09 + 11 are the equipment pillar. If a vertical is planned for paid distribution, its pillar shots must be flawless.
- **Verticals are produced with Flow's Video Resizer** from the 16:9 masters, then re-supered with centred type. See `06-assembly-post.md` §6.
