# 00 — Campaign Brief

**Campaign:** ASMC Corporate Film 2026 — "48 Hours"
**Deliverable:** 105-second corporate film, 16:9, bilingual (Arabic + English versions)
**Production tool:** Google Flow (Veo 3.1 for video, Nano Banana for stills)
**Owner:** ASMC marketing

---

## 1. Verified brand fact sheet

Everything below is taken from the live site build (`asmc_build/`). **Do not add facts that are not on this list.** No invented certifications, client names, awards, or inflated numbers.

| Field | Value |
|---|---|
| Legal name (EN) | Adel Saad Al-Matar Contracting Est. |
| Legal name (AR) | مؤسسة عادل سعد المطر للمقاولات |
| Short name | ASMC |
| Domain | asmc.com.sa |
| C.R. | 2250007423 |
| HQ | 9P9F+6Q, Al Hofuf, Al-Ahsa, Eastern Province, Saudi Arabia |
| Coordinates | 25.3833, 49.5833 |
| Email | contact@asmc.com.sa |
| Phone | +966 54 955 6517 · +966 59 080 7169 |
| Hours | Sun–Thu 08:00–17:00 · Sat 08:00–12:00 · Fri closed |
| Coverage claim | Riyadh, Jeddah, Dammam, Al Hofuf and the entire Kingdom |

### Services (5 — exact names)
1. Manpower Supply — skilled engineers, technicians and labor for every industry across Saudi Arabia
2. Equipment Rental — heavy machinery, construction tools and safety equipment with 24/7 support
3. Construction — full-cycle construction from planning to handover with experienced site teams
4. Material Supply
5. Contracting Services

### Trades named on site
Engineers · technicians · drivers · plumbers · electricians

### Industries served (9)
Construction · Oil & Gas · Industrial & Manufacturing · Infrastructure · Power & Utilities · Facilities Management · Hospitality · Logistics & Warehousing · Retail & Commercial

### Stats (use verbatim — do not round up)
| 3+ | Years of Service |
| 500+ | Skilled Workers |
| 60+ | Projects Delivered |
| 24/7 | Equipment Support |

### Differentiators (verbatim from "Why us")
- **Compliance first** — Every worker arrives with valid Iqama, medical clearance and insurance. Saudi labour-law compliant from day one.
- **Speed of mobilisation** — Crews mobilised within **48–72 hours** from our Eastern-Province base. **Replacements within 24 hours.**
- **Execute & report** — On-site supervision, weekly reporting and live replacement support.

### Mission
Deliver exceptional manpower solutions and reliable equipment rental that exceed client expectations — at the highest standards of safety and professionalism.

### Vision
To be the most trusted partner for construction and industrial companies across Saudi Arabia — known for our skilled workforce and reliable equipment solutions.

### Brand palette (from site tokens)
| Token | Hex | Use |
|---|---|---|
| Cream / foreground | `#f4f1ea` | Type, logo, supers |
| Stone / muted | `#8a857a` | Secondary type, gradients |
| Near-black / background | `#0a0a0a` | Backgrounds, letterbox |

Typographic system on site: Inter (sans) · Instrument Serif italic (display accent) · JetBrains Mono (uppercase, `0.18em` tracking, eyebrow labels) · Noto Naskh Arabic (Arabic). **Match this in supers.**

---

## 2. Strategic frame

### Objective
Generate qualified inbound enquiries from project owners and main contractors in the Eastern Province and Kingdom-wide, and give ASMC sales a credible asset to open conversations with.

### Primary audience (ICP)
Project managers, procurement managers and site directors at main contractors, industrial plants and O&G subcontractors operating in KSA. They control manpower budgets and are personally exposed when a project slips.

### Their real problem
Not "we need workers." It's: *a crew didn't show, or showed without valid papers, and now the schedule and my reputation are bleeding.* Manpower risk is schedule risk is career risk.

### Positioning
ASMC is the manpower partner that removes the two things that actually hurt you: **delay** and **non-compliance**. 48–72 hour mobilisation. 24-hour replacement. Every worker arrives with valid Iqama, medical clearance and insurance.

### Single message the film must land
> When your crew is short, ASMC has compliant, supervised people on your site within 48 hours.

### Deliberate strategic choice: do not sell scale
ASMC is 3+ years old with 60+ projects. Competing on size against multinational suppliers is a losing and dishonest frame. The film competes on **responsiveness, compliance and supervision** — which is both true and the sharper B2B wedge. Every number shown on screen is the real one.

### Tone
Documentary-industrial. Confident, unhurried, factual. Editorial restraint that matches the website — no stock-music triumphalism, no drone-porn montage, no fake testimonials.

### What the film must never do
- Show a competitor, a real client's name/logo, or an identifiable third-party brand
- Imply certifications ASMC does not hold (no ISO, no Aramco approval, no Saudization percentage)
- Depict unsafe practice (missing PPE, unbraced work at height, no harness) — it destroys credibility with this exact audience
- Show female workers in site roles that would misrepresent ASMC's actual workforce; the compliance/admin role is where the female character sits, and in this cast we have opted for no female character (see `01-character-bible.md` §5)

---

## 3. Deliverables & distribution

| Cut | Length | Ratio | Placement |
|---|---|---|---|
| Master AR | 105s | 16:9 | asmc.com.sa/ar hero, YouTube, sales deck |
| Master EN | 105s | 16:9 | asmc.com.sa hero, YouTube, LinkedIn |
| Cutdown 60s | 60s | 16:9 | LinkedIn feed, YouTube pre-roll |
| Cutdown 30s | 30s | 16:9 + 9:16 | Paid social, Google Video |
| Verticals | 3 × 15s | 9:16 | Reels / TikTok / Shorts — one per pillar (mobilisation, compliance, equipment) |

All cuts are derived from **one** picture lock. See `06-assembly-post.md`.

---

## 4. KPIs

| Metric | Target |
|---|---|
| Enquiries attributed to film (form + WhatsApp + call) | Primary |
| 50% view-through rate on YouTube/LinkedIn | ≥ 30% |
| Homepage hero → contact conversion lift | Measure vs. pre-launch baseline |
| Sales usage (reps opening with the film) | Qualitative, review at 30 days |

Tag every placement with UTMs. The 30s paid cut and the 105s organic cut must be measured separately or the numbers are meaningless.

---

## 5. Non-negotiable production constraints

These are derived from what Google Flow can and cannot actually do. They shape every prompt in this pack.

1. **No on-camera dialogue.** Characters work, gesture, nod, point — they never speak to camera. Voiceover is recorded separately per language. This is what makes one visual render serve both AR and EN, and it sidesteps Veo's unreliable Arabic lip-sync.
2. **No text or logos generated in-video.** Veo cannot reliably render Arabic script or the ASMC mark. All supers, stats and branding are added with Flow's **Type Overlays** tool or in post.
3. **Platform UI is screen-replaced in post.** Never prompt for the ASMC dashboard — Veo will invent a fake one. Prompt a blank/glowing monitor, then composite real screenshots.
4. **Max 3 ingredient images per generation.** Ingredient recipes in `05-video-prompts.md` never exceed 3.
5. **`Extend` is banned for character shots.** It regenerates from the final second only and drifts faces. Permitted for the landscape establishing shot only.
6. **Generate 16:9 at 1080p.** Verticals come from the Video Resizer tool, never from re-generating.
7. **AI disclosure.** All Flow output carries SynthID. Follow platform disclosure rules where required, and never present generated footage as documentary evidence of a specific real project.

---

## 6. Read next

`01-character-bible.md` → `02-location-bible.md` → `03-script-shotlist.md` → `04-image-prompts.md` → `05-video-prompts.md` → `06-assembly-post.md` → `07-consistency-qa.md`

Start production with the **consistency spike** in `07-consistency-qa.md` §1. Do not generate all 15 shots before that spike passes.
