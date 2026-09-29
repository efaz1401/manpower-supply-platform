# 02 — Location & Style Bible

Five sets. Like the identity strings, each location has a **locked environment string** pasted verbatim into every prompt shot there, and one **Nano Banana location plate** used as an ingredient.

**Naming in Flow:** `LOC-DESERT.png`, `LOC-SITE.png`, `LOC-OFFICE-CLIENT.png`, `LOC-OPS.png`, `LOC-WORKSHOP.png`, plus `STYLE.png`.

---

## The style plate — the most important asset in the pack

One image defines the grade, and it occupies the third ingredient slot in **every** generation. Without it, 15 clips generated over several sessions will not cut together — they will drift in colour temperature, contrast and grain, and no amount of post can fully rescue that.

### Locked style string — paste verbatim in every prompt

```
Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Warm desert daylight, deep amber and dust-ochre midtones, cool steel-grey shadows, gentle
film grain, subtle halation on highlights, natural handheld weight, no colour saturation
boost, restrained editorial grade.
```

### Nano Banana `STYLE.png` prompt

```
A cinematic still frame with no people in it: a shaft of warm dusty sunlight cutting across
a bare concrete industrial wall, fine airborne dust visible in the beam, a length of steel
scaffold tube in the foreground out of focus.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes.
Warm desert daylight, deep amber and dust-ochre midtones, cool steel-grey shadows, gentle
film grain, subtle halation on highlights, restrained editorial grade. Colour palette
limited to cream, warm stone-grey, dust ochre and near-black.

16:9, photorealistic, no text, no logos, no people, no watermark.
```

> The palette named — cream / stone-grey / ochre / near-black — is deliberately the site's own palette (`#f4f1ea`, `#8a857a`, `#0a0a0a`). The film will feel like the website without anyone being able to say why.

---

## LOC-DESERT — Al-Ahsa at first light

**Used in:** Shots 01, 02
**Time:** 05:20, civil dawn. The only set with cool light.

### Locked environment string

```
The Al-Ahsa oasis edge in Eastern Saudi Arabia at first light: a two-lane asphalt road
running straight through low apricot-coloured sand dunes, dense date-palm groves in the
middle distance, a pale blue-grey pre-sunrise sky with a thin band of warm gold on the
horizon, faint ground haze.
```

### Notes
- Al-Ahsa is a **real UNESCO-listed oasis**. Veo will approximate it, not reproduce it. Date palms + low dunes + oasis greenery is the correct signature — **not** empty Rub' al Khali dunes, which is the generic "Saudi desert" cliché and geographically wrong for Al Hofuf.
- If geographic authenticity matters to leadership, this is the one shot worth sourcing a **real drone plate** for and using as the image-to-video source. It is the first thing the audience sees and it sets the credibility of everything after.
- No camels. No falcons. No sand-dune-sunset postcard framing.

### Nano Banana `LOC-DESERT.png` prompt

```
A wide empty landscape photograph with no people and no vehicles: the Al-Ahsa oasis edge in
Eastern Saudi Arabia at first light: a two-lane asphalt road running straight through low
apricot-coloured sand dunes, dense date-palm groves in the middle distance, a pale
blue-grey pre-sunrise sky with a thin band of warm gold on the horizon, faint ground haze.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes,
gentle film grain, restrained editorial grade, cool dawn tones.

16:9, photorealistic, no text, no logos, no people, no camels, no watermark.
```

---

## LOC-SITE — the construction site, Al Hofuf

**Used in:** Shots 04, 10, 11, 12, 13, 14, 15 — the primary set, seven shots.
**Time:** progresses from mid-morning (Act 2) to late-afternoon golden hour (Act 5). This time progression is the film's emotional arc and must be prompted explicitly per shot.

### Locked environment string

```
A mid-rise reinforced-concrete construction site on the edge of Al Hofuf, Saudi Arabia:
exposed concrete columns and floor slabs four storeys up, steel scaffolding with green
safety mesh, rebar bundles and stacked concrete blocks on graded sand ground, a yellow
mobile crane, low date palms and flat sand-coloured buildings beyond the site fence, hard
Gulf sunlight and fine airborne dust.
```

### Per-shot lighting override
| Shots | Override to append |
|---|---|
| 04 | `mid-morning, high hard sunlight, short shadows, the site is quiet and under-staffed` |
| 10, 11, 12 | `late morning, strong directional sunlight, the site is busy and active` |
| 13, 14, 15 | `late afternoon golden hour, long warm shadows, low amber sun` |

### Notes
- Every visible worker wears a hard hat and hi-vis. **Always.** One bare-headed worker in the background and the credibility with a site director is gone.
- Background workers: `anonymous, heavily out of focus, faces not visible, backs to camera`.
- No client logos on the crane, no readable signage anywhere.

### Nano Banana `LOC-SITE.png` prompt

```
A wide establishing photograph of an empty construction site with no people visible: a
mid-rise reinforced-concrete construction site on the edge of Al Hofuf, Saudi Arabia:
exposed concrete columns and floor slabs four storeys up, steel scaffolding with green
safety mesh, rebar bundles and stacked concrete blocks on graded sand ground, a yellow
mobile crane, low date palms and flat sand-coloured buildings beyond the site fence, hard
Gulf sunlight and fine airborne dust.

Documentary-industrial cinematography, shot on ARRI Alexa with vintage anamorphic primes,
warm desert daylight, dust-ochre midtones, cool steel-grey shadows, gentle film grain,
restrained editorial grade.

16:9, photorealistic, no text, no logos, no signage, no people, no watermark.
```

---

## LOC-OFFICE-CLIENT — Faisal's site office

**Used in:** Shot 03
**Time:** mid-morning.

### Locked environment string

```
A functional site project office in Saudi Arabia: a large printed construction drawing
spread across a laminate desk, a desktop monitor turned away from camera, a hard hat resting
on a filing cabinet, aluminium-framed window with dusty vertical blinds half open casting
hard slatted light, plain off-white walls, a wall-mounted programme chart.
```

### Notes
- The monitor faces **away** from camera or is switched off. This is a hard rule — see the screen-replacement policy in §"Screens" below.
- Slatted blind light is doing narrative work: it should feel like pressure, not comfort.
- No visible text on the drawings or the programme chart. Prompt `drawings and charts blurred and illegible`.

### Nano Banana `LOC-OFFICE-CLIENT.png` prompt

```
An interior photograph of an empty office with no people: a functional site project office
in Saudi Arabia: a large printed construction drawing spread across a laminate desk, a
desktop monitor turned away from camera, a hard hat resting on a filing cabinet,
aluminium-framed window with dusty vertical blinds half open casting hard slatted light,
plain off-white walls, a wall-mounted programme chart.

Documentary-industrial cinematography, shot on ARRI Alexa, warm interior daylight, cool
grey shadows, gentle film grain, restrained editorial grade.

16:9, photorealistic, drawings and charts blurred and illegible, no readable text, no
logos, no people, no watermark.
```

---

## LOC-OPS — the ASMC operations room

**Used in:** Shots 06, 07, 08
**Time:** interior, time-neutral.

### Locked environment string

```
The ASMC operations room in Al Hofuf: a clean modern office with a long desk, three large
dark monitors, a wall-mounted map of Saudi Arabia with no readable labels, neat stacks of
document folders, a black desk phone, warm cream walls and near-black joinery, soft daylight
from a window on the left.
```

### Notes
- **This is the set where the ASMC platform appears.** All three monitors are prompted as `dark, switched off, or glowing with soft indistinct blue light`. The real Livewire dashboard screenshots are composited in post. Do not negotiate on this — a hallucinated dashboard is the single most embarrassing thing that can end up in this film.
- Cream walls + near-black joinery is the website palette made physical.
- The wall map must have **no readable labels** — generated Arabic or English place names will be garbled.

### Nano Banana `LOC-OPS.png` prompt

```
An interior photograph of an empty modern office with no people: the ASMC operations room in
Al Hofuf: a clean modern office with a long desk, three large dark switched-off monitors, a
wall-mounted map of Saudi Arabia with no readable labels, neat stacks of document folders, a
black desk phone, warm cream walls and near-black joinery, soft daylight from a window on
the left.

Documentary-industrial cinematography, shot on ARRI Alexa, soft warm interior light, cool
grey shadows, gentle film grain, restrained editorial grade.

16:9, photorealistic, monitors dark and switched off, map labels illegible, no readable
text, no logos, no people, no watermark.
```

---

## LOC-WORKSHOP — the fabrication bay

**Used in:** Shot 09
**Time:** interior, hard shafted light.

### Locked environment string

```
A steel fabrication workshop bay: a heavy steel workbench with clamped steel plate, a large
roller shutter door half open at the rear letting a hard shaft of dusty sunlight across the
floor, coiled welding cable, gas cylinders chained upright to the wall, dark soot-marked
concrete floor, tool boards in shadow.
```

### Notes
- This is the film's **darkest and most contrasted** shot by design — the weld arc is the only bright source and it earns the "skilled" claim in one image.
- Gas cylinders **chained upright** is a safety detail this audience will register subconsciously. Keep it in the string.

### Nano Banana `LOC-WORKSHOP.png` prompt

```
An interior photograph of an empty workshop with no people: a steel fabrication workshop
bay: a heavy steel workbench with clamped steel plate, a large roller shutter door half open
at the rear letting a hard shaft of dusty sunlight across the floor, coiled welding cable,
gas cylinders chained upright to the wall, dark soot-marked concrete floor, tool boards in
shadow.

Documentary-industrial cinematography, shot on ARRI Alexa, high contrast, hard shafted
sunlight, deep steel-grey shadows, gentle film grain, restrained editorial grade.

16:9, photorealistic, no text, no logos, no people, no watermark.
```

---

## Screens policy (applies to every set)

| Rule | Reason |
|---|---|
| Never prompt a visible UI, dashboard, spreadsheet or app screen | Veo invents plausible-looking fake software. It will not be your product and it will look like it. |
| Prompt monitors as `dark`, `switched off`, or `glowing with soft indistinct blue light` | Gives post a clean surface to track a real screenshot onto |
| Never prompt readable text anywhere — signage, drawings, ID cards, maps, vest lettering | Generated text is garbled in Latin script and much worse in Arabic |
| Phone screens: prompt `phone screen dark and not visible` or shoot from behind | Same reason |

Every prompt in `05-video-prompts.md` carries these as exclusions. They are not optional.

---

## Continuity map

| Shot | Location | Light | Characters |
|---|---|---|---|
| 01 | LOC-DESERT | Pre-dawn | — |
| 02 | LOC-DESERT | Dawn | — |
| 03 | LOC-OFFICE-CLIENT | Mid-morning | Faisal |
| 04 | LOC-SITE | Mid-morning, quiet | Faisal |
| 05 | LOC-OFFICE-CLIENT | Mid-morning | Faisal |
| 06 | LOC-OPS | Interior day | Khalid |
| 07 | LOC-OPS | Interior day | — (hands/documents) |
| 08 | LOC-OPS | Interior day | Khalid |
| 09 | LOC-WORKSHOP | Hard shaft | Ravi |
| 10 | LOC-SITE | Late morning, busy | Ravi |
| 11 | LOC-SITE | Late morning, busy | — (equipment) |
| 12 | LOC-SITE | Late morning, busy | Khalid + Ravi |
| 13 | LOC-SITE | Golden hour | — (wide) |
| 14 | LOC-SITE | Golden hour | Khalid + Faisal |
| 15 | LOC-SITE | Golden hour | All three |

The light progression — cool dawn → hard midday → warm golden hour — is the whole emotional arc carried without a single word. Never break it for a "nicer" looking generation.
