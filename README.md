# EcoDrive Driver Registration & Fleet Administration Portal

A clean, production-ready full-stack driver onboarding web application developed for **EcoDrive Pvt Ltd** (a green logistics startup). This portal streamlines independent commercial driver registrations by leveraging automated geographical lookup capabilities and rigorous age validation metrics while offering a secure administration interface for fleet overview and dynamic record management.


---

## Core Technical Features

- **Mandatory Native PHP Architecture:** Developed entirely in raw, vanilla PHP using **PDO (PHP Data Objects)** wrapper patterns. It explicitly implements Object-Oriented database programming, eliminating vulnerable legacy procedural models.

- **Asynchronous Location Detection (AJAX API):** Implements asynchronous frontend lookups to the `ipinfo.io` JSON API via the JavaScript Fetch API. It handles public terminal network request handshakes to auto-populate `Country`, `Region`, and `City` input vectors seamlessly on document initialization without page refreshes.

- **Dual-Layer Defensive Age Verification (24+):** Implements explicit safety checks on both entry layers:
  - **Frontend (UI-Layer):** JavaScript event listeners monitor date picking inputs to calculate biological age bounds and forcefully reset structural data fields upon underage anomaly detection.

  - **Backend (API-Layer):** Core PHP scripts recalculate temporal distance bounds via `DateTime` intervals before issuing SQL payloads, safely rejecting manual manipulation bypasses.

- **State-Aware Conditional Rendering:** Imploys structural state-tracking utilizing native `$_SESSION` global maps to split layout contexts based on active Role-Based Access Control (RBAC):

  - **Drivers** receive a clean confirmation layout compiling their newly added profile metrics straight from state memory into clean, isolated data matrix cards.
  - **Admins** access an operational fleet log tracking view featuring real-time inline row transformations for record alterations.

- **Administrative Operations Capabilities:** Features an administrative sub-string pattern matcher (`LIKE :search`) that filters active directory data tables based on string conditions. Custom table transformations convert static display row data into functional HTML input components instantly when the **Edit** action is called.

- **Clean Responsive Layout UI:** Designed using utility classes from the Tailwind CSS framework via secure content distribution network (CDN) linkages.

---

##  Repository Directory Structure

The codebase adheres strictly to an organized monolithic architecture, segregating operational controllers from state layouts:

```text
eco/
│
├── config/
│   └── database.php      # Secure PDO database connector instance configuration
│
├── public/
│   ├── js/
│   │   └── location.js   # AJAX script handling ipinfo API fetching & age boundary guards
│   ├── index.php         # Public Driver Profile Registration UI form terminal
│   ├── login.php         # Administrative Security Authentication gateway layout
│   └── dashboard.php     # State-aware administrative dashboard & driver receipt cards
│
├── actions/
│   ├── process_reg.php   # Onboarding processor executing DB insertions & age-checks
│   ├── auth_login.php    # Resolves password verify hash comparisons matching Admins
│   └── admin_action.php  # Handles administrative state drop queries & inline modifications
└── README.md             # GitHub documentation and deployment framework
```

---
##  Administrative Access Profile

o To review the administrative backend features, log tracking grids, record editing modules, or elimination handlers, use the pre-seeded admin profile:

Portal Sign-In Endpoint URL: http://localhost/ecodrive-portal/public/login.php

Username Variable: admin

Password Variable: admin123