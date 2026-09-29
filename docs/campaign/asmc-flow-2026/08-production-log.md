# 08 — Production Execution Log & Asset Storage Guide

**Campaign:** ASMC Corporate Film 2026 — "48 Hours"  
**Client:** Adel Saad Al-Matar Contracting Est. ([asmc.com.sa](https://asmc.com.sa))  
**Production Platform:** Google Flow (Pro Subscription)  
**Flow Project URL:** [https://labs.google/fx/tools/flow/project/bfe2356d-2201-43a6-a5fd-1d2852eb7490](https://labs.google/fx/tools/flow/project/bfe2356d-2201-43a6-a5fd-1d2852eb7490)  
**Log Date:** 2026-08-15  

---

## 1. Asset Storage Locations & Access Instructions

All generated media and project artifacts are saved in the following locations:

### A. Google Flow Workspace (Cloud & Browser Session)
* **Project URL:** [`https://labs.google/fx/tools/flow/project/bfe2356d-2201-43a6-a5fd-1d2852eb7490`](https://labs.google/fx/tools/flow/project/bfe2356d-2201-43a6-a5fd-1d2852eb7490)
* **What is saved here:**
  * **26 Still Image Assets (`A1` – `A23`):** Style plates, location plates, character plates, combined reference plates, and start/end staging keyframes.
  * **9 Video Outputs (`B01` – `B05`):** 1080p 16:9 Veo 3.1 renders for Shots 01 through 05.
* **How to access / download:**
  1. Open the project URL above in your browser (logged into your Google Flow Pro account).
  2. Open the **Media Library / Assets Panel** on the left or bottom panel.
  3. Hover over any generated image or video render and click **Download (1K Original / 1080p MP4)** to save to your local machine (`Downloads` folder).

### B. Campaign Production Pack Folder (Local Repository)
* **Path:** `c:\Users\MSI\manpower-platform\docs\campaign\asmc-flow-2026\`
* **Files saved here:**
  * [`README.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/README.md) — Master guide and project reading order.
  * [`00-campaign-brief.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/00-campaign-brief.md) — Brand facts, ICP, strategic positioning.
  * [`01-character-bible.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/01-character-bible.md) — Character identity strings and wardrobe continuity.
  * [`02-location-bible.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/02-location-bible.md) — Style plate and environment set specs.
  * [`03-script-shotlist.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/03-script-shotlist.md) — Voiceover scripts (AR/EN), timing sheets, cutdowns.
  * [`04-image-prompts.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/04-image-prompts.md) — Nano Banana image prompt specifications.
  * [`05-video-prompts.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/05-video-prompts.md) — Veo 3.1 video prompt specifications & shot difficulty tiers.
  * [`06-assembly-post.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/06-assembly-post.md) — NLE assembly, audio mixing, screen replacement, RTL supers.
  * [`07-consistency-qa.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/07-consistency-qa.md) — QA checklists, consistency spike criteria, triage rules.
  * [`08-production-log.md`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/08-production-log.md) — **Current production execution log & asset storage guide.**
  * [`prompts-paste.txt`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/prompts-paste.txt) — Copy-paste prompt sheet for Google Flow.

### C. System Brain & Session Artifacts
* **Path:** `C:\Users\MSI\.gemini\antigravity\brain\6ca23b3b-808d-4381-9a34-d2d66b2be892\`
* **Files saved here:**
  * `walkthrough.md` — Complete production walkthrough and asset summary.
  * `recording.webm` — Browser execution recordings from automation runs.

---

## 2. Production Status Summary

- **Stills Generation (Nano Banana 2):** 26 Assets (`A1` through `A23`) — **100% COMPLETE**
- **Head-Turn Consistency Gate:** Khalid (`A7`), Ravi (`A8`), Faisal (`A9`) — **100% PASSED**
- **Video Renders Completed (Veo 3.1 1080p):** Shots `B01`, `B02`, `B03`, `B04`, `B05` — **5 SHOTS GENERATED (9 Video Outputs)**
- **Remaining Video Shots (`B06` – `B15`):** Staged and prompt-ready in `prompts-paste.txt`. Paused due to Google Flow AI Credit Limit.

---

## 3. Asset Roster & Media ID Tracking

### Part A — Nano Banana 2 Stills & Reference Plates (All 26 Complete in Flow)

| Code | Asset Name | Staging / Input References | Outputs | Storage Location | QA Status |
| :--- | :--- | :--- | :---: | :--- | :---: |
| `A1` | `STYLE.png` | Master style plate (ARRI Alexa, dust-ochre & steel-grey palette) | 2 | Flow Media Library | Approved |
| `A2` | `LOC-DESERT.png` | Al-Ahsa oasis edge pre-dawn environment plate | 2 | Flow Media Library | Approved |
| `A3` | `LOC-SITE.png` | Mid-rise concrete site establishing environment plate | 2 | Flow Media Library | Approved |
| `A4` | `LOC-OFFICE-CLIENT.png` | Client site office interior environment plate | 2 | Flow Media Library | Approved |
| `A5` | `LOC-OPS.png` | ASMC operations room interior environment plate | 2 | Flow Media Library | Approved |
| `A6` | `LOC-WORKSHOP.png` | Steel fabrication workshop bay environment plate | 2 | Flow Media Library | Approved |
| `A7` | `CHAR-KHALID.png` | Khalid (ASMC Supervisor, white hard hat) portrait plate | 4 | Flow Media Library | Approved (Head-turn passed) |
| `A8` | `CHAR-RAVI.png` | Ravi (Technician, orange hard hat, navy coveralls) portrait plate | 4 | Flow Media Library | Approved (Head-turn passed) |
| `A9` | `CHAR-FAISAL.png` | Faisal (Client PM, thobe + ghutra, grey hard hat) portrait plate | 4 | Flow Media Library | Approved (Head-turn passed) |
| `A10` | `PAIR-KHALID-RAVI.png` | Combined reference plate for 2-shot supervision | 2 | Flow Media Library | Approved |
| `A11` | `PAIR-KHALID-FAISAL.png` | Combined reference plate for 2-shot handover | 2 | Flow Media Library | Approved |
| `A12` | `TRIO-ALL.png` | Combined hero trio reference plate | 2 | Flow Media Library | Approved |
| `A13`–`A23` | `FRAME-03-START` to `FRAME-15-START` | 11 Shot staging keyframes & end frames | 22 | Flow Media Library | Approved |

---

### Part B — Veo 3.1 1080p Video Renders

| Shot | Timecode | Description | Mode / Inputs | Status & Media IDs | Storage Location |
| :---: | :---: | :--- | :--- | :--- | :--- |
| **B01** | 0:00–0:06 | Aerial dawn over Al-Ahsa palm groves | Ingredients (`LOC-DESERT`, `STYLE`) | **GENERATED (2 Outputs)**<br>• `88c3a0f9-58d2-4460-a093-d8889af17380`<br>• `ddfc3e2d-a7f5-4cd7-b5e4-9d22c9e696aa` | Flow Project Canvas |
| **B02** | 0:06–0:11 | Ground level sunrise, distant pickup truck | Ingredients (`LOC-DESERT`, `STYLE`) | **GENERATED (2 Outputs)**<br>• `245ddd21-83fe-49c6-876f-500e7d6827b5`<br>• `a3fd38de-4598-44ea-a2d4-855157c8da98` | Flow Project Canvas |
| **B03** | 0:11–0:18 | Faisal checking watch in site office | Frames to Video (`FRAME-03-START`) | **GENERATED (1 Output)**<br>• `a2719238-7cb4-4f3d-9438-1d8a9c83ad92` | Flow Project Canvas |
| **B04** | 0:18–0:26 | Faisal observing quiet under-staffed site | Frames to Video (`FRAME-04-START`) | **GENERATED (2 Outputs)**<br>• `3873cdd8-982a-4eec-a83f-1724bfd39f08`<br>• `a4adb318-a8ef-488e-a4bf-bc5af06fd135` | Flow Project Canvas |
| **B05** | 0:26–0:31 | Insert on Faisal lifting desk phone handset | Ingredients (`CHAR-FAISAL`, `LOC-OFFICE`, `STYLE`) | **GENERATED (2 Outputs)**<br>• `b4812729-70b1-4ee7-968a-c916d824a0a7`<br>• `2466e1e9-6053-4584-8162-1e78b249c206` | Flow Project Canvas |
| **B06** | 0:31–0:38 | Khalid taking call in ASMC ops room | Frames to Video (`FRAME-06-START`) | Staged — Paused at AI Credit Limit | `prompts-paste.txt` B06 |
| **B07** | 0:38–0:45 | High angle insert on hands stamping folders | Frames to Video (`FRAME-07-START`) | Staged & Ready | `prompts-paste.txt` B07 |
| **B08** | 0:45–0:50 | Khalid setting phone down & walking out | Ingredients (`B06 frame`, `LOC-OPS`, `STYLE`) | Staged & Ready | `prompts-paste.txt` B08 |
| **B09** | 0:50–0:58 | Ravi striking weld arc & inspecting bead | Frames to Video (`FRAME-09-START`) | Staged & Ready | `prompts-paste.txt` B09 |
| **B10** | 0:58–1:05 | Ravi walking through busy active site | Ingredients (`CHAR-RAVI`, `LOC-SITE`, `STYLE`) | Staged & Ready | `prompts-paste.txt` B10 |
| **B11** | 1:05–1:12 | Yellow mobile crane lifting rebar | Ingredients (`LOC-SITE`, `STYLE`) | Staged & Ready | `prompts-paste.txt` B11 |
| **B12** | 1:12–1:19 | Khalid & Ravi 2-shot on scaffold | Frames to Video (`FRAME-12-START`) | Staged & Ready | `prompts-paste.txt` B12 |
| **B13** | 1:19–1:26 | Golden hour crane-up reveal over full site | Start & End (`FRAME-13-START` to `END`) | Staged & Ready | `prompts-paste.txt` B13 |
| **B14** | 1:26–1:34 | Khalid & Faisal walking 2-shot & handshake | Start & End (`FRAME-14-START` to `END`) | Staged & Ready | `prompts-paste.txt` B14 |
| **B15** | 1:34–1:42 | All three standing in line facing structure | Frames to Video (`FRAME-15-START`) | Staged & Ready | `prompts-paste.txt` B15 |

---

## 4. Instructions for Resuming Video Generations (`B06` – `B15`)

When your Google Flow AI credits refresh or are topped up:
1. Open the project URL: [`https://labs.google/fx/tools/flow/project/bfe2356d-2201-43a6-a5fd-1d2852eb7490`](https://labs.google/fx/tools/flow/project/bfe2356d-2201-43a6-a5fd-1d2852eb7490).
2. Open [`prompts-paste.txt`](file:///c:/Users/MSI/manpower-platform/docs/campaign/asmc-flow-2026/prompts-paste.txt) locally.
3. Copy prompts `B06` through `B15` sequentially into Flow with settings:
   - **Model:** `Veo 3.1` (Quality)
   - **Aspect Ratio:** `16:9`
   - **Resolution:** `1080p`
   - **Output Count:** `1` (x1 output)
4. Download finished MP4 video files to your local editing environment.
