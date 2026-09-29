# 06 — Assembly & Post

From 15 approved clips to seven finished deliverables.

---

## §1 — Order of operations

```
1.  Picture lock in Flow Scenebuilder          → rough cut, no audio, no supers
2.  Export picture lock                        → 1080p 16:9 master, 102s
3.  Strip Veo's generated audio                → keep as reference only
4.  Record AR voiceover to picture             → then time EN to the AR
5.  Build the audio bed                        → ambience + music + VO
6.  Screen replacement (Shot 06 / 08)          → real dashboard composited
7.  Logo + supers, EN version                  → Type Overlays or NLE
8.  Logo + supers, AR version                  → mirrored, Arabic-Indic numerals
9.  Subtitles                                  → AR cut gets EN subs, EN cut gets AR subs
10. Grade pass                                 → unify any residual clip drift
11. Cutdowns (60s, 30s)                        → from the same lock
12. Verticals (3 × 15s)                        → Video Resizer, re-supered
13. Delivery encodes
```

**Never re-open picture after step 7.** Every super, subtitle and cutdown is timed to the lock. Moving a cut point after supers exist means retiming both language versions.

---

## §2 — Picture lock in Scenebuilder

Drop the 15 approved clips into Scenebuilder in shot order and trim to the cut lengths in `03-script-shotlist.md`.

### Trim discipline
Every clip is generated at 8s (two at 6s) and cut shorter. **Trim from the head, not the tail, where you have the choice.** Veo's first 4–6 frames often carry a subtle settling artefact as the model resolves the start frame; the tail is usually cleaner.

### Cut points that matter

| Cut | Type | Note |
|---|---|---|
| 02 → 03 | Hard cut on light change | Cool dawn to warm interior. The jolt is intentional — it starts Act 2. |
| 04 → 05 | Cut on action | Cut *before* Faisal finishes turning; the hand reaching for the phone completes the movement. |
| 05 → 06 | Cut on the phone | Handset lifts in 05, phone is already at Khalid's ear in 06. Never show a connection being made — the ellipsis is what makes it feel fast. |
| 08 → 09 | Hard cut, black-frame adjacent | Khalid clears frame into a bright office; Shot 09 opens dark. Let the 2-frame darkness happen. |
| 12 → 13 | Cut on the crane-up start | Land the `48–72 HOURS` super 4s into Shot 13, at the top of the rise. |
| 14 → 15 | Cut on the handshake release | Do not hold the handshake. Cut as hands part. |

### The Shot 04 / Shot 10 rhyme
These two shots are the film's argument — same site, empty then full. Both are the same location at the same focal length. If a viewer does not consciously register the comparison, the film has failed at its one job. Consider matching the framing more tightly in the grade, and do **not** cut either shot so short that the site itself doesn't register.

---

## §3 — Audio

### Discard Veo's audio (mostly)
Veo 3.1 generates synchronised audio, and the ambience it produces for these prompts is genuinely usable as a **starting bed**. But: it will not be consistent in level or tone across 15 separately generated clips, and inconsistent room tone is more noticeable to an audience than inconsistent picture.

**Recommended:** keep Veo's ambience as a low-level texture layer (−24 to −18 dB), and lay a **continuous** designed ambience bed over the top so the film has one acoustic identity rather than fifteen.

### Layers

| Layer | Level | Content |
|---|---|---|
| VO | −6 dB, ducked bed under | One artist per language |
| Ambience bed | −18 dB, continuous | Wind + distant site, running the full 105s |
| Veo clip audio | −24 dB | Per-clip texture, heavily filtered |
| Spot SFX | −12 dB | Only three: the phone lift (0:28), the stamp (0:44), the arc strike (0:51) |
| Music | −20 dB, rising to −14 dB | See below |

### The three spot effects
These are the only sounds that should be *noticed*. Each one punctuates a claim: the phone lift is the client's decision, the stamp is compliance, the arc strike is skill. Pull them forward in the mix and let everything else sit back.

### Music
Sparse and restrained. Low sustained strings or a soft synth pad with a slow pulse — no percussion until the golden hour turn at Shot 13, and even then keep it minimal. **No triumphant brass, no corporate-uplift piano, no trailer hits.**

The film's tone is documentary-industrial; the moment the music tries to make the audience feel something, they stop believing the footage. Enter at 0:11 (Act 2), lift at 1:19 (Act 5), resolve under the end card.

### VO recording notes
- Record the **Arabic first** and lock its timing. Then direct the English read to land on the same picture beats. MSA runs 10–15% longer; timing to the English first will leave the Arabic cramped, and the AR cut is the one most of your audience will watch.
- One artist per language, one session per language. No mixing takes across sessions — room tone will not match.
- Direct for *under*-performance. The lines are factual; delivering "48 to 72 hours" as a boast undercuts it. Deliver it as a fact.
- Get an alt take of every line at a slower pace. You will want them for the cutdowns.

---

## §4 — Screen replacement (Shots 06 and 08)

The three monitors in the ops room were prompted dark specifically so real product UI can be composited in.

1. Capture real screenshots from the ASMC platform. Suggested screens: the executive dashboard, the workers list with compliance status pills, and a document-expiry alert view. Log in as `admin@asmc.com.sa` against seeded demo data — **never** use real worker names, Iqama numbers, or client names.
2. **Scrub PII before capture.** Reseed with `php artisan db:seed --class=DemoSeeder` and verify the visible rows are demo data. A single real Iqama number in a public marketing film is a serious problem.
3. Corner-pin track each screenshot onto its monitor in the NLE. Add a subtle screen-glow spill onto Khalid's face and the desk to seat it.
4. Match the screen's colour temperature to the room — the raw screenshot will be too blue and too bright. Knock it back at least 20%.
5. **Capture the Arabic (RTL) version of the UI for the AR cut.** The platform is bilingual; using the LTR English dashboard in the Arabic film is exactly the kind of detail that undermines a localisation claim.

> If capturing clean screenshots is not feasible in time, leave the monitors dark. A dark monitor is neutral. A fake or PII-leaking dashboard is a liability.

---

## §5 — Supers, logo and subtitles

### Toolchain
Flow's **Type Overlays** tool handles animated text inside Flow. For final delivery, doing supers in the NLE (Premiere / DaVinci / After Effects) gives more control over Arabic shaping and is strongly recommended for the AR cut.

### Typography — match the website
| Element | Font | Treatment |
|---|---|---|
| Eyebrow labels (EN) | JetBrains Mono | Uppercase, `0.18em` letter-spacing |
| Statements (EN) | Inter | Regular weight, tight tracking |
| All Arabic | Noto Naskh Arabic | Never letter-space Arabic |
| Colour | Cream `#f4f1ea` | On a 30% near-black `#0a0a0a` scrim if legibility demands it |

### Critical Arabic typesetting rules

Arabic is not "English with a different font." Get these wrong and the AR cut looks amateur to every native reader:

1. **Never apply letter-spacing/tracking to Arabic.** It breaks the connected script into disconnected glyphs. Set tracking to 0.
2. **Verify glyph shaping** in the render, not just the NLE preview. Some tools fail to apply contextual forms and output isolated letters — the text will look like nonsense. Check a render frame at 100%.
3. **Use Arabic-Indic numerals** in the AR cut: `٤٨–٧٢`, `٢٤`, `+٥٠٠`, `+٦٠`, `٩`. Western digits in an Arabic film signal a lazy translation.
4. **Mirror everything.** Super positions, animation entry direction, the order of the stat row, the logo/text lockup. Lower-left becomes lower-right.
5. **Right-align Arabic text blocks**, not left or centre (except the centred hero supers).

Super copy and timings are in `03-script-shotlist.md` § "On-screen supers".

### Logo
The ASMC mark is added in post — **never generated**. Source it from the site build (`asmc_build/` — the footer SVG: circle with an inscribed 'A' peak and a dot, cream-to-stone gradient `#f4f1ea` → `#8a857a`). Use the vector, not a screenshot.

Appearances: end card only. Optionally a small corner bug from 1:19. Do not bug the whole film.

### Subtitles
| Cut | Burned-in | Sidecar |
|---|---|---|
| AR master | English subtitles, bottom centre | `.srt` EN |
| EN master | Arabic subtitles, bottom centre | `.srt` AR |
| Verticals | Burned-in, large, centred | — |

Both language cuts must be watchable **muted** — most LinkedIn and mobile views are silent. Burn subtitles into the social cuts, not just the sidecar.

---

## §6 — Cutdowns and verticals

### 60s and 30s
Cut from the same picture lock using the shot maps in `03-script-shotlist.md` § "Cutdown maps". Re-time supers; do not re-generate anything.

### Verticals (3 × 15s, 9:16)
1. Export the three 15s sequences from the 16:9 lock.
2. Run each through Flow's **Video Resizer** tool to reframe to 9:16.
3. **Check every reframe manually.** Automated reframing will centre on whatever it thinks the subject is. Shot 04's whole point is the *empty space* around Faisal — a resizer will crop to Faisal and destroy the shot. Shot 15's three-figure line will not survive a 9:16 crop at all.
4. Re-super with centred, larger type. Never reuse 16:9 super positions in 9:16.
5. Burn in subtitles. Assume muted playback.

**Honest caveat:** Shots 04, 13 and 15 are composed for 16:9 and will lose most of their meaning in 9:16. If the verticals matter commercially, the better answer is to generate 3–4 **native 9:16 shots** for social rather than cropping these. That is a separate small prompt set using the same ingredient plates — the plates are ratio-agnostic, so the incremental cost is low.

---

## §7 — Grade pass

Even with `STYLE.png` in every generation, 15 clips will carry residual variance. Budget a real grade pass.

1. Pick Shot 12 as the reference — it is mid-film, mid-light, and contains two characters.
2. Match every other clip to it: lift/gamma/gain, then a secondary on skin tones.
3. **Skin tone consistency is the priority.** Character faces shifting warmer or cooler between shots is the most perceptible form of drift and the easiest to fix in grade.
4. Apply one unified film-grain pass over the whole timeline at the end. This is the single most effective trick for making separately generated clips feel like one shoot — a common grain structure reads as a common camera.
5. Preserve the deliberate light arc: cool dawn (01–02) → hard neutral midday (03–12) → warm golden hour (13–15). Do **not** flatten it into one look.

---

## §8 — Delivery encodes

| Deliverable | Codec | Notes |
|---|---|---|
| Master AR 105s 16:9 | ProRes 422 HQ | Archive |
| Master EN 105s 16:9 | ProRes 422 HQ | Archive |
| Web hero AR/EN | H.264 MP4, 1080p, ~8 Mbps | Also export a muted, subtitled version for autoplay hero use |
| YouTube AR/EN | H.264 MP4, 1080p, ~12 Mbps | |
| LinkedIn 60s | H.264 MP4, 1080p, < 200 MB | Burned subs |
| Paid 30s (16:9 + 9:16) | H.264 MP4 | Burned subs |
| Verticals 3 × 15s | H.264 MP4, 1080×1920 | Burned subs |
| Sidecars | `.srt` AR + EN per master | |

### Website integration note
If the film goes into the site hero, it must not block content or hurt Core Web Vitals: `poster` frame, `preload="none"`, autoplay muted only, and respect `prefers-reduced-motion` — consistent with how motion is already handled in this project. A 105s hero video that delays LCP will cost more in bounce than the film earns in trust.

---

## §9 — Compliance and disclosure

- **SynthID.** All Flow output carries an invisible SynthID watermark. This is not removable and should not be attempted.
- **Platform disclosure.** YouTube and Meta both require disclosure of realistic synthetic media. Tick the "altered or synthetic content" box. Failing to do so risks the asset being labelled or down-ranked for you, which is worse than declaring it.
- **Never present generated footage as a specific real project.** Do not caption any shot with a real client, site or contract. The film depicts ASMC's *capabilities*, not a documented job. Written claims elsewhere in the campaign must be independently true.
- **The three characters are not employees.** Never name them or imply they are real staff.
- **Every number is real** — 500+, 60+, 3+, 24/7, 48–72 hours, 24-hour replacement — all sourced from asmc.com.sa. If any of these changes on the site, this film goes out of date. Note the dependency.
