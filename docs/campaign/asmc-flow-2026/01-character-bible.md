# 01 — Character Bible

Three characters. Three is the ceiling, not a preference — Veo 3.1's **Ingredients to Video** accepts a maximum of **3 reference images per generation**, and every shot needs one slot for the location plate and one for the style plate. See §6 for how multi-character shots get around this.

---

## How consistency is actually enforced

Four mechanisms, in order of importance:

1. **The identity string.** Each character has a locked ~40-word description below. It is pasted **verbatim, word for word, in every single prompt** that character appears in. Never paraphrase it, never shorten it, never "improve" it. Paraphrasing is the #1 cause of face drift.
2. **The ingredient plate.** One approved Nano Banana portrait per character, uploaded to the Flow project once and dragged in as an ingredient for every shot.
3. **Frame chaining.** After a shot is approved, use Flow's **Save frame** on a good frame of that character and use it as the start frame or as a replacement ingredient plate for later shots. This is drift-resistant because it re-anchors to real generated pixels rather than a text description.
4. **Wardrobe invariance.** Each character has exactly one costume for the entire film. No costume changes. A costume change forces the model to re-imagine the person and is where faces most reliably break.

**Naming convention in Flow:** name the ingredient files `CHAR-KHALID.png`, `CHAR-RAVI.png`, `CHAR-FAISAL.png`. Do not rename mid-project.

---

## Character 1 — KHALID (the ASMC supervisor)

**Role in the film:** the protagonist. ASMC's site supervisor. He is the answer to the client's problem. He is competent and calm and never dramatic. The audience should trust him.

**Screen name:** none. He is never named on screen. He represents ASMC's supervision, not an individual employee.

### Locked identity string — paste verbatim

```
Khalid, a 38-year-old Saudi man, medium-brown skin, short black hair, full neatly trimmed
black beard, calm steady dark brown eyes, strong straight nose, lean athletic build,
178cm tall. He wears a white ASMC hi-vis safety vest with reflective silver bands over a
grey long-sleeve work shirt, a clean white hard hat, and clear safety glasses pushed up on
the brim. A black lanyard with a plain ID card hangs at his chest.
```

### Continuity rules
- Hard hat is **white** — white is the supervisor colour on Saudi sites and reads instantly as authority. Never swap to orange or yellow.
- Beard length is fixed. If a generation gives him a short stubble or a long beard, reject it.
- Safety glasses sit **pushed up on the hard hat brim** in dialogue-adjacent/observation shots, **down over the eyes** only in the workshop shot (Shot 09). This is the one permitted variation and it is a safety-credibility requirement.
- No sunglasses. No smoking. No phone in hand except Shot 05 (the call) and Shot 14.
- Expression range: neutral-to-slight-nod. He does **not** grin at camera. One small closed-mouth smile permitted, in Shot 15 only.

### Nano Banana character-sheet prompt

```
Professional character reference sheet on a flat neutral light-grey studio background,
five views of the same man in a single image, evenly lit: front view, three-quarter left
view, profile left view, three-quarter right view, and back view.

The man: Khalid, a 38-year-old Saudi man, medium-brown skin, short black hair, full neatly
trimmed black beard, calm steady dark brown eyes, strong straight nose, lean athletic
build, 178cm tall. He wears a white ASMC hi-vis safety vest with reflective silver bands
over a grey long-sleeve work shirt, a clean white hard hat, and clear safety glasses
pushed up on the brim. A black lanyard with a plain ID card hangs at his chest.

Identical face, identical wardrobe, identical proportions in all five views. Neutral
confident expression. Photorealistic, 85mm portrait lens character reference, sharp focus,
no text, no labels, no watermarks, no logos.
```

### Nano Banana ingredient-plate prompt (the one you upload as `CHAR-KHALID.png`)

```
Photorealistic medium close-up portrait of a man, chest-up, centred, facing camera, on a
soft neutral grey seamless background, even soft studio lighting.

Khalid, a 38-year-old Saudi man, medium-brown skin, short black hair, full neatly trimmed
black beard, calm steady dark brown eyes, strong straight nose, lean athletic build. He
wears a white ASMC hi-vis safety vest with reflective silver bands over a grey long-sleeve
work shirt, a clean white hard hat, and clear safety glasses pushed up on the brim. A
black lanyard with a plain ID card hangs at his chest.

Calm neutral expression, direct gaze. 85mm lens, shallow depth of field, sharp on the
eyes. No text, no logos, no writing on the vest or hard hat, no watermark.
```

> **Note the "no writing on the vest" instruction.** You want the vest blank. Real ASMC branding is added in post if needed. Generated lettering will be garbled and will look fake to anyone who reads it.

---

## Character 2 — RAVI (the skilled technician)

**Role in the film:** the craft. He is the proof that "skilled" is not a marketing word. His hands and his focus carry the middle of the film. He is treated with dignity — never as anonymous labour, never as a background prop.

**Screen name:** none.

### Locked identity string — paste verbatim

```
Ravi, a 32-year-old South Asian man, warm brown skin, short black hair, clean-shaven with
a faint moustache shadow, focused dark eyes, sturdy compact build, 172cm tall. He wears
navy-blue ASMC work coveralls with reflective bands at the sleeves, an orange hard hat,
heavy scuffed leather work gloves, and steel-toe boots. A welding helmet is tipped up on
his forehead.
```

### Continuity rules
- Hard hat is **orange** — trade colour, distinct from Khalid's white at a glance even in wide shots. This colour separation is deliberate and load-bearing for readability.
- Coveralls are **navy blue**, lightly worn and dusty — never brand-new, never filthy. Worn-in reads as real; pristine reads as an advert.
- Welding helmet is tipped **up** on his forehead in all shots except Shot 09, where it is **down** during the actual arc.
- Gloves are **on** in every shot he appears in. A skilled tradesman without gloves is a red flag to this audience.
- Never shown idle, never shown looking at camera.

### Nano Banana character-sheet prompt

```
Professional character reference sheet on a flat neutral light-grey studio background,
five views of the same man in a single image, evenly lit: front view, three-quarter left
view, profile left view, three-quarter right view, and back view.

The man: Ravi, a 32-year-old South Asian man, warm brown skin, short black hair,
clean-shaven with a faint moustache shadow, focused dark eyes, sturdy compact build, 172cm
tall. He wears navy-blue ASMC work coveralls with reflective bands at the sleeves, an
orange hard hat, heavy scuffed leather work gloves, and steel-toe boots. A welding helmet
is tipped up on his forehead.

Identical face, identical wardrobe, identical proportions in all five views. Calm focused
expression. Photorealistic, 85mm portrait lens character reference, sharp focus, no text,
no labels, no watermarks, no logos.
```

### Nano Banana ingredient-plate prompt (`CHAR-RAVI.png`)

```
Photorealistic medium close-up portrait of a man, chest-up, centred, facing camera, on a
soft neutral grey seamless background, even soft studio lighting.

Ravi, a 32-year-old South Asian man, warm brown skin, short black hair, clean-shaven with
a faint moustache shadow, focused dark eyes, sturdy compact build. He wears navy-blue ASMC
work coveralls with reflective bands at the sleeves, an orange hard hat, heavy scuffed
leather work gloves, and steel-toe boots. A welding helmet is tipped up on his forehead.

Calm focused expression, direct gaze. 85mm lens, shallow depth of field, sharp on the
eyes. No text, no logos, no writing on the coveralls or hard hat, no watermark.
```

---

## Character 3 — FAISAL (the client project manager)

**Role in the film:** the audience's surrogate. He has the problem in Act 2 and the relief in Act 5. Every viewer in the ICP should recognise themselves in him. He is never made to look incompetent — his site is short-staffed through no fault of his own.

**Screen name:** none.

### Locked identity string — paste verbatim

```
Faisal, a 45-year-old Saudi man, olive-brown skin, short greying black hair, short
salt-and-pepper beard, sharp intelligent eyes with faint lines, solid build, 180cm tall.
He wears a crisp white thobe with a folded white ghutra headscarf, and over it a plain
grey hi-vis site vest and a grey hard hat carried in his left hand or worn on site.
```

### Continuity rules
- The **thobe + ghutra under a site vest** is the correct and instantly readable silhouette for a Saudi client-side project manager doing a site walk. This is the single most important cultural-authenticity detail in the film. Get it wrong and the local audience disengages immediately.
- Ghutra is **plain white**, folded, **no black agal cord** — the agal is typically removed for site work under a hard hat. Do not prompt an agal.
- Hard hat is **grey** (visitor/client colour) — third distinct colour, completing the white/orange/grey hierarchy.
- He is **not** in PPE in Shot 03 (his office) — thobe and ghutra only, no vest, no hard hat.
- Expression: concerned in Act 2, measured approval in Act 5. Never angry, never gushing.

### Nano Banana character-sheet prompt

```
Professional character reference sheet on a flat neutral light-grey studio background,
five views of the same man in a single image, evenly lit: front view, three-quarter left
view, profile left view, three-quarter right view, and back view.

The man: Faisal, a 45-year-old Saudi man, olive-brown skin, short greying black hair,
short salt-and-pepper beard, sharp intelligent eyes with faint lines, solid build, 180cm
tall. He wears a crisp white thobe with a folded white ghutra headscarf, and over it a
plain grey hi-vis site vest and a grey hard hat.

Identical face, identical wardrobe, identical proportions in all five views. Measured
serious expression. Photorealistic, 85mm portrait lens character reference, sharp focus,
no text, no labels, no watermarks, no logos, no black agal cord on the headscarf.
```

### Nano Banana ingredient-plate prompt (`CHAR-FAISAL.png`)

```
Photorealistic medium close-up portrait of a man, chest-up, centred, facing camera, on a
soft neutral grey seamless background, even soft studio lighting.

Faisal, a 45-year-old Saudi man, olive-brown skin, short greying black hair, short
salt-and-pepper beard, sharp intelligent eyes with faint lines, solid build. He wears a
crisp white thobe with a folded white ghutra headscarf, and over it a plain grey hi-vis
site vest and a grey hard hat.

Measured serious expression, direct gaze. 85mm lens, shallow depth of field, sharp on the
eyes. No text, no logos, no watermark, no black agal cord on the headscarf.
```

---

## §5 — Cast decisions and why

**Why no female character.** The compliance/HR/ops-coordinator role is the natural place for a female character and would be culturally accurate in a Saudi corporate office. We have left it out of this film for one reason: with a 3-image ingredient cap, a fourth character cannot be reliably locked, and a character who appears in one shot and never returns reads as tokenism. If ASMC wants female representation in the campaign — and there is a good case for it in recruitment-facing content — it belongs in a **separate** careers film with its own character lock, not bolted onto this one.

**Why no crowd of extras.** Background workers are prompted as **anonymous, out-of-focus, back-to-camera** figures only. Any background face Veo generates will drift between shots and an attentive viewer notices. Depth of field is the tool that solves this: `background workers heavily out of focus, faces not visible`.

**Why three distinct hard-hat colours.** White (supervisor) / orange (trade) / grey (client visitor) means the viewer can read the site hierarchy in a wide shot with no dialogue and no supers. In a film with no on-camera speech, silhouette and colour do the narrative work.

---

## §6 — Multi-character shots and the 3-ingredient cap

Two-character and three-character shots cannot carry one plate per person plus location plus style. The workaround is to **pre-compose a combined plate in Nano Banana** and spend a single ingredient slot on it.

| Shot | Characters | Ingredient slots used |
|---|---|---|
| Single-character shots | 1 | `CHAR-X.png` + `LOC-X.png` + `STYLE.png` |
| Shot 12 (Khalid + Ravi) | 2 | `PAIR-KHALID-RAVI.png` + `LOC-SITE.png` + `STYLE.png` |
| Shot 14 (Khalid + Faisal) | 2 | `PAIR-KHALID-FAISAL.png` + `LOC-SITE.png` + `STYLE.png` |
| Shot 15 (all three) | 3 | `TRIO-ALL.png` + `LOC-SITE.png` + `STYLE.png` |

The combined-plate prompts are in `04-image-prompts.md` §3. Build them **only after** all three single plates are approved, and build them by referencing the approved single plates as inputs so the faces carry over.

---

## §7 — Voice (post-production, not generated)

Voice consistency is deliberately moved out of Veo. Nobody speaks on camera, so there is no lip-sync to match and no voice to keep consistent across 15 generations.

| Version | Voice brief |
|---|---|
| Arabic | Male, 35–50, Modern Standard Arabic with a natural Gulf/Najdi colouring. Low-mid register, unhurried, authoritative but not "announcer." Think state-infrastructure documentary, not radio advert. |
| English | Male, 35–50, neutral international English, faint Arabic accent is a plus for authenticity. Same pacing and register as the Arabic read so both cuts sit identically against picture. |

**Critical:** record both reads against the **same picture lock** at the same pace. Write the Arabic script first and time the English to it — Arabic MSA runs roughly 10–15% longer than equivalent English, so timing to the English first will leave the Arabic cramped. See `03-script-shotlist.md`.

One voice artist per language for the whole film. Do not mix.
