# ASMC Corporate Film 2026 — "48 Hours"

Production pack for a **105-second bilingual (Arabic + English) corporate film** for [asmc.com.sa](https://asmc.com.sa), built for **Google Flow** (Veo 3.1 + Nano Banana 2).

---

## Google Flow Active Project

* **Project URL:** [https://labs.google/fx/tools/flow/project/bfe2356d-2201-43a6-a5fd-1d2852eb7490](https://labs.google/fx/tools/flow/project/bfe2356d-2201-43a6-a5fd-1d2852eb7490)

---

## Where Generated Assets Are Saved

1. **Google Flow Cloud Workspace (Online & Downloads):**
   * Access via the project URL above while logged into your Google Flow Pro account.
   * **Stills:** All 26 still image assets (`A1`–`A23`) are saved in the project Media Library.
   * **Videos:** Generated 1080p Veo 3.1 video renders (`B01`–`B05`, 9 playable MP4 outputs) are saved on the Project Canvas & Media Library. Hover over any render and select **Download (1K Original / 1080p MP4)** to save to your local machine's `Downloads` folder.
2. **Local Campaign Folder (`c:\Users\MSI\manpower-platform\docs\campaign\asmc-flow-2026\`):**
   * Production specifications, character/location bibles, prompts, assembly instructions, and the execution log.
3. **Session Artifacts:**
   * Full walkthrough and session execution logs stored in system artifacts.

---

## Files, in reading order

| File | What it's for |
|---|---|
| [`00-campaign-brief.md`](00-campaign-brief.md) | Strategy, ICP, positioning, **verified brand fact sheet**, KPIs, hard production constraints |
| [`01-character-bible.md`](01-character-bible.md) | 3 characters, locked identity strings, wardrobe continuity, character-sheet prompts |
| [`02-location-bible.md`](02-location-bible.md) | 5 sets + the style plate, locked environment strings, continuity map |
| [`03-script-shotlist.md`](03-script-shotlist.md) | Timing sheet, AR + EN voiceover scripts, on-screen supers, shot-by-shot direction, cutdown maps |
| [`04-image-prompts.md`](04-image-prompts.md) | Every Nano Banana prompt: plates, combined plates, start/end frames + asset manifest |
| [`05-video-prompts.md`](05-video-prompts.md) | 15 Veo 3.1 prompts with ingredient recipes, accept/reject criteria, generation log |
| [`06-assembly-post.md`](06-assembly-post.md) | Picture lock, audio, screen replacement, RTL typesetting, cutdowns, delivery |
| [`07-consistency-qa.md`](07-consistency-qa.md) | **The consistency spike**, QA checklists, drift triage, credit budget, known limitations |
| [`08-production-log.md`](08-production-log.md) | **Production Execution Log & Asset Storage Guide** (Status, media IDs, storage locations) |
| [`README.md`](README.md) | Master overview and asset location directory |
| [`prompts-paste.txt`](prompts-paste.txt) | Every prompt, stripped and numbered, for pasting straight into Flow |

---

## Core Technical Decisions

1. **Nobody speaks on camera.** Voiceover is recorded in post, so a single visual render serves both the Arabic and English cuts — and Veo's unreliable Arabic lip-sync never becomes a problem. Every prompt excludes dialogue and lip movement.
2. **No text or logos are generated.** Veo cannot render Arabic script or the ASMC mark reliably. All supers and branding are added with Flow's Type Overlays tool or in an NLE.
3. **The platform UI is composited in post.** Monitors are prompted dark. Real screenshots (demo data only, no PII) are tracked on afterwards. Never let Veo invent a dashboard.
4. **`Extend` is banned except on Shot 01.** It regenerates from the final second and compounds face drift. Consistency comes from ingredient plates, Frames-to-Video, and saved-frame chaining instead.

---

## Dependency Note

This film hard-codes stats from asmc.com.sa. **If those numbers change on the site, the film is out of date.** Set a review trigger rather than finding out from a client.
