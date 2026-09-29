# 07 — Consistency QA & Production Protocol

Read this **before** generating anything.

---

## §1 — The consistency spike (do this first)

Do not generate 15 shots and hope. Generate three, and prove the pipeline works.

### Why
Character drift is not a problem you fix at the end — it is a problem you either prevent at the plate stage or live with. If Khalid's face does not hold across three shots in three different locations with three different lighting setups, it will not hold across fifteen, and you will have burned most of your credits finding out.

### The spike

**Prerequisite:** `STYLE.png`, all `LOC-*.png`, and all three `CHAR-*.png` plates approved and head-turn tested (`04-image-prompts.md` §2).

Generate exactly these three, one output each:

| Spike shot | Tests |
|---|---|
| **Shot 06** (Khalid, ops room, soft interior) | Does Khalid hold in flat interior light? Are the monitors actually dark? |
| **Shot 09** (Ravi, workshop, extreme contrast) | Does Ravi hold under the hardest lighting in the film? Is the helmet down during the arc? |
| **Shot 14** (Khalid + Faisal, golden hour, two-shot + handshake) | Does a *pair* plate hold two faces? Does Frames-to-Video deliver the handshake? Does Khalid still match his Shot 06 self across a totally different light? |

### Pass criteria

```
[ ] Khalid in Shot 14 is recognisably the same man as Khalid in Shot 06
[ ] Ravi's face in Shot 09 matches CHAR-RAVI.png
[ ] Faisal's thobe + folded white ghutra under a grey hi-vis vest reads correctly — no agal
[ ] All three hard hat colours are correct (white / orange / grey)
[ ] No mouth movement in any of the three clips
[ ] No readable text anywhere in any of the three clips
[ ] All monitors in Shot 06 are dark with no UI
[ ] Ravi's welding helmet is DOWN during the arc in Shot 09
[ ] The Shot 14 handshake actually happens and is framed side-on
[ ] All three clips look like the same film — same grain, same colour behaviour
```

### If the spike fails

| Failure | Fix — in this order |
|---|---|
| Face drifts between 06 and 14 | Regenerate `CHAR-KHALID.png` with a **more distinctive, more ordinary** face. Idealised faces drift toward a generic model look. Re-run the head-turn test. |
| Pair plate loses one face | Rebuild `PAIR-KHALID-FAISAL.png` by feeding both approved single plates in as image inputs. If it still fails, split Shot 14 into two singles cut together. |
| Wardrobe changes shot to shot | You paraphrased the identity string. Go back and paste it verbatim. This is the most common cause. |
| Text appears despite exclusions | Add the offending surface explicitly: `the clipboard is blank`, `the vest has no lettering`. |
| Clips don't look like the same film | `STYLE.png` was not in the ingredient list, or you used Veo 3.1 Fast for one of them. |

**Do not proceed to full production until all ten pass criteria are met.** Iterating the spike is cheap. Iterating fifteen shots is not.

---

## §2 — Per-shot QA checklist

Run on every clip before it enters the picture lock. Any single failure = regenerate. Do not "fix it in post" — most of these cannot be fixed in post.

### Identity
```
[ ] Face matches the character's ingredient plate
[ ] Face matches the same character in adjacent shots
[ ] Hard hat colour correct — Khalid white / Ravi orange / Faisal grey
[ ] Wardrobe identical to the identity string, no additions or substitutions
[ ] Khalid: safety glasses on the brim (down only in Shot 09 — he isn't in it, so always on brim)
[ ] Ravi: gloves on, welding helmet up (down only during the Shot 09 arc)
[ ] Faisal: folded white ghutra, NO black agal cord
[ ] Faisal: no PPE in Shot 03, PPE in Shots 04 / 14 / 15
```

### The hard rules
```
[ ] No mouth movement, no speech, no visible attempt at dialogue
[ ] No readable text anywhere — signage, drawings, ID cards, maps, clothing, equipment
[ ] No brand names or model numbers on the crane or any equipment
[ ] No user interface on any monitor or phone screen
[ ] No worker anywhere in frame without a hard hat AND hi-vis
[ ] No background face resolves in focus
[ ] Nobody looks at the camera (except nobody ever does — this film has no lens contact)
```

### Craft
```
[ ] Hands: five fingers, correct anatomy, no merging
[ ] Physics: loads have weight, sparks fall, dust drifts
[ ] Light direction consistent with adjacent shots in the same location
[ ] Time-of-day correct per the continuity map in 02-location-bible.md
[ ] Grain and colour behaviour consistent with the other approved clips
[ ] Camera move is the one specified (static means static)
[ ] No slow-motion, no lens flare stars, no oversaturation
```

### Safety credibility — the one your audience will actually notice
```
[ ] Helmet down while welding
[ ] Gas cylinders chained upright (Shot 09)
[ ] No unbraced work at height without visible edge protection
[ ] Crane load not swinging over people
[ ] Nobody standing under a suspended load
```

> Site directors and HSE managers are a meaningful slice of the ICP. A single unsafe frame tells them ASMC doesn't understand site safety — which is precisely the opposite of what the film is arguing. This checklist section is not pedantry; it is the credibility of the whole asset.

---

## §3 — Drift triage

When a face starts sliding, escalate in this order. Do not skip to the bottom.

| Level | Action | Cost |
|---|---|---|
| 1 | Regenerate with the identical prompt — Veo output varies run to run | 1 gen |
| 2 | Confirm the identity string is **verbatim**. Diff it character-by-character against `01-character-bible.md`. | free |
| 3 | Confirm the correct ingredient plate is attached and `STYLE.png` is in the list | free |
| 4 | Replace the character plate with a **saved frame** from an already-approved clip of that character in similar light | 1 gen |
| 5 | Add a Nano Banana **start frame** for the shot so composition and face are both pinned | 1 image + 1 gen |
| 6 | Restage the shot to reduce face exposure — wider, more profile, or over-shoulder | 1 image + 1 gen |
| 7 | Split a multi-character shot into single-character shots cut together | 2 gens |

**Level 2 resolves this more often than anything else.** Paraphrasing an identity string — even trivially, even improving it — is the number one cause of drift. The string is a key, not prose.

### The saved-frame chain (level 4, explained)

Flow's **Save frame** lets you extract a still from a generated clip. A frame from an approved clip is a *better* character reference than the original studio plate for subsequent shots, because it already carries the film's grade, lighting behaviour and grain. Build the chain forward:

```
CHAR-KHALID.png → Shot 06 (approved) → save frame → Shot 08, Shot 12
                                     → Shot 12 (approved) → save frame → Shot 14, Shot 15
```

Anchor each new shot to the most recently approved appearance **in similar light**. Do not chain a golden-hour shot off an interior frame.

---

## §4 — Credit budget

Estimate, assuming a competent operator and the spike passing on the second attempt.

| Stage | Units | Notes |
|---|---|---|
| Style + location plates | 12 images | 6 assets × 2 attempts |
| Character sheets | 6 images | 3 × 2 |
| Character ingredient plates | 12 images | 3 × 4 attempts — spend here, it's the cheapest place to buy reliability |
| Combined plates | 9 images | 3 × 3 |
| Start / end frames | 22 images | 11 frames × 2 |
| **Image subtotal** | **~61 images** | |
| Consistency spike | 6 videos | 3 shots, allow one full re-run |
| Simple shots (01, 02, 05, 07, 11) | 10 videos | ~2 attempts each |
| Character shots (03, 04, 06, 08, 10) | 15 videos | ~3 attempts each |
| Hard shots (09, 12, 13, 14, 15) | 20 videos | ~4 attempts each — 09 may need 6 |
| Contingency | 10 videos | |
| **Video subtotal** | **~61 videos** | ≈ 8 minutes of generated footage for 105 seconds of film |

**Roughly 61 images + 61 video generations.** A 7:1 shoot ratio is normal for this kind of work; if you find yourself well past 100 video generations, the problem is upstream in the plates, not in the prompts. Stop and re-plate.

### Where to spend and where not to

**Spend:** character ingredient plates, Shot 09 (the weld), Shot 14 (the handshake), Shot 15 (the final composition). These four carry the film.

**Don't spend:** Shots 02, 05, 11 — take the second acceptable generation and move on. Nobody will remember them, and they are structurally load-bearing only in that they exist.

---

## §5 — Session hygiene

- **One Flow project for the whole film.** Ingredients live in the project; splitting across projects means re-uploading and risks version mismatch.
- **Never rename an ingredient file mid-project.** You will lose track of which plate produced which approved clip.
- **Keep the generation log** in `05-video-prompts.md` filled in. When a shot needs regenerating in three weeks, you need to know which plate and which prompt version produced the version you liked.
- **Never mix Veo 3.1 and Veo 3.1 Fast** in the final cut. Fast is fine for spike/test passes and produces visibly different texture. Note the model on every kept generation.
- **Approve in context, not in isolation.** A clip that looks great alone can be wrong for the film. Drop each candidate into the Scenebuilder timeline next to its neighbours before approving.

---

## §6 — Final delivery QA

Before anything is published.

### Both cuts
```
[ ] Runtime is 105s
[ ] No generated text visible in any frame — scrub at 25% speed to check
[ ] No fake UI on any monitor
[ ] Every visible worker has a hard hat and hi-vis
[ ] Character faces consistent across the full timeline
[ ] Light arc intact: cool dawn → hard midday → golden hour
[ ] Unified grain pass applied
[ ] Audio: VO clear at -6dB, three spot effects audible, music restrained
[ ] Watchable and comprehensible fully muted
[ ] End card details correct against the fact sheet in 00-campaign-brief.md
```

### Brand facts — verify against `00-campaign-brief.md` §1
```
[ ] Legal name spelled exactly: Adel Saad Al-Matar Contracting Est.
[ ] Arabic name exactly: مؤسسة عادل سعد المطر للمقاولات
[ ] Domain: asmc.com.sa
[ ] Email: contact@asmc.com.sa
[ ] Phone: +966 54 955 6517
[ ] Stats verbatim: 500+ skilled workers, 60+ projects delivered, 9 industries
[ ] 48–72 hours mobilisation, 24-hour replacement — stated exactly, not rounded
[ ] No invented certifications, clients, awards or Saudization figures
```

### Arabic cut specifically
```
[ ] Arabic VO signed off by a native speaker BEFORE recording (see 03-script-shotlist.md)
[ ] Arabic glyph shaping verified in a RENDERED frame at 100%, not just NLE preview
[ ] Zero letter-spacing on all Arabic text
[ ] Arabic-Indic numerals used throughout (٤٨ ٢٤ ٥٠٠ ٦٠ ٩) — no Western digits
[ ] All super positions mirrored for RTL
[ ] Stat row order reversed
[ ] Animation entry directions mirrored
[ ] Arabic text blocks right-aligned
[ ] AR cut uses the Arabic (RTL) dashboard screenshots, not the English LTR ones
[ ] AR cut leads the end card with the Arabic name
```

### Legal / disclosure
```
[ ] Synthetic-content disclosure ticked on YouTube and Meta
[ ] No real client, site, contract or project named or implied
[ ] No real worker PII in any composited screenshot — demo data only
[ ] No third-party logos, brand names or equipment model numbers visible
[ ] Characters never named or implied to be real employees
```

---

## §7 — Known limitations, stated plainly

Things this pack cannot solve, so nobody is surprised late:

1. **Al-Ahsa will be an approximation.** Veo generates a plausible Eastern-Province oasis, not the actual UNESCO oasis. Anyone from Al Hofuf may notice. One real drone plate for Shot 01 removes this risk cheaply and is the single highest-value piece of real footage you could add.
2. **Faces will not be perfect.** Even with a disciplined plate pipeline, expect subtle variation across 15 shots. The mitigations — profile angles, shallow depth of field, restrained close-ups, unified grain — are built into the shot design, but this is an inherent limit of the tooling in 2026, not a process failure.
3. **The verticals are compromised by design.** Shots 04, 13 and 15 are composed for 16:9 and lose meaning in a 9:16 crop. If social is a primary channel, generate native 9:16 shots instead of resizing.
4. **This is not documentary footage.** It depicts ASMC's capability, not a real project. Every factual claim in the film is verifiable from asmc.com.sa, but the imagery is synthetic and must never be captioned as a specific job.
5. **The film has a shelf life tied to the website.** It hard-codes 500+, 60+, 9 industries and 48–72 hours. When those change on the site, the film is out of date. Plan a refresh trigger rather than discovering the mismatch when a client points it out.
