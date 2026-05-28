# Prompt: Build ASAI Landing Page

## Role

You are a senior front-end developer building a corporate landing page for a security agency using Vue.js.

## Objective

Create a professional, corporate-style landing page for **Annapolis Security Agency, Inc. (ASAI)**.

---

## Design Specifications

### Color Palette

| Token       | Hex       | Usage                  |
|-------------|-----------|------------------------|
| Background  | `#f2f6f6` | Page background        |
| Primary     | `#4a5f80` | Headings, nav, buttons |
| Accent      | `#ddb360` | Highlights, CTAs       |

### Icons

- Use **Lucide Icons** (`lucide-vue-next`) for all iconography (nav items, capability cards, services, footer, etc.)

### Header / Navigation

- **Sticky** header that stays fixed at the top on scroll
- **Compact** design: slim height, logo on the left, nav links on the right
- Subtle shadow or border on scroll to indicate elevation
- Smooth transition when scrolling (e.g., slight background opacity change)

### Layout

- Hero section: image on the left, text content on the right
- Use iPhone/MacBook-style device mockups for imagery
- Corporate aesthetic: clean, minimal, professional typography
- Responsive design (mobile-first)

### Assets

- Logo: `/images/logo.png`
- Hero image: `/images/hero.png`

---

## Content

### Hero / About Section

**Company Name:** Annapolis Security Agency, Inc. (ASAI)

**Tagline / Motto:** "TRANSFORMING SECURITY FOR A BETTER BUSINESS"

**Description:**

Formerly UBP Security Agency, ASAI was established by Senior Police Officer Atty. Urvi B. Pascual to provide security and safety services since June 2014. Registered with the SEC under Number CS201809381. In 2018, the company merged with the AsiaLink Group of Companies — one of the largest Financing Corporations nationwide — strengthening its financial and network capabilities.

ASAI is a service-oriented corporation with strong financial capabilities that puts client needs and satisfaction as its utmost priority. We are technically and financially ready to undertake contractual obligations, backed by highly skilled security professionals. We adhere to the changes of time and technology while retaining lessons of the past to cope with future challenges.

---

### Vision Section

**Heading:** Vision Statement

- "A world-class Security Service Company"
- "An ISO-Certified Security Management Company"
- "To initiate the professionalization of enterprise security practice in the country — creating an environment of professional acceptance of holistic security concepts, so that threats to life, limb, and operational integrity of secured facilities are substantially thwarted, if not altogether eliminated."
- "To develop and maintain a secure environment where maximum productivity is assured and people may enjoy the fruits of their labor free of anxiety and fear."

---

### Mission Section

**Heading:** Mission Statement

"To upgrade the level of security service by applying an integrated system approach for greater specialization — adapting to current and evolving security and safety challenges, making us a trustworthy partner for business development of our current and future clients."

---

### Agency Capabilities Section

**Heading:** Agency Capabilities

Display as a grid or card-based layout. Each capability has a number, title, and short description.

1. **Audited Financial Transparency** – ASAI maintains strong financial statements regularly audited by Reyes Tacandong & Co., ensuring transparency, compliance, and credibility with clients, partners, and investors.
2. **Experienced Leadership and Industry Expertise** – Led by seasoned professionals with extensive experience in security operations, law enforcement coordination, and corporate management — ensuring strategic direction, operational discipline, and industry best practices.
3. **Scalable Security Operations** – Operational capability to deploy security personnel efficiently across multiple sites nationwide. Our scalable structure supports both small and large-scale requirements while maintaining consistent service quality.
4. **Highly Trained and Professional Security Personnel** – Security officers undergo rigorous recruitment screening, training, and continuous professional development to meet the highest standards of discipline, professionalism, and operational readiness.
5. **PNP-SOSIA Licensed Training Director** – A dedicated Training Director licensed by the Philippine National Police – Supervisory Office for Security and Investigation Agencies (PNP-SOSIA), ensuring all training programs comply with national regulatory standards.
6. **Integrated Security Solutions** – Beyond manpower deployment: risk assessment, security planning, incident management, and coordination with local authorities for comprehensive client protection.
7. **Logistically Prepared Operations** – Adequate logistical resources including mobility, communication systems, and operational support equipment for rapid deployment and effective emergency response.
8. **Strong Government and Law Enforcement Coordination** – Professional coordination with PNP, NBI, MMDA, and other national authorities for efficient incident handling, investigations, and security operations.

---

### Services Offered Section

**Heading:** Services Offered

Display as a two-column or icon-grid list.

- Retail / Mall Security
- BPO Security
- Aviation Security
- Hotel Security
- Industrial Security
- Guard Force Management
- Event Security
- VIP Protection Services
- Security Consultancy (Casino, Hotel, Retail, Logistics & Supply Chain)
- K9 Services
- Environmental, Occupational, Health and Safety Assessment
- Security Survey
- Vulnerability Assessment
- VIP Protection
- E-Guarding

---

### Quality Assurance Section

**Heading:** Our Commitment to Quality

**Description:**

To guarantee the consistent quality of our services, we have instituted both internal and external audit teams dedicated to monitoring and maintaining our standards. Currently, we proudly serve clients across Luzon, Visayas, and Mindanao.

We remain steadfast in our mission to uphold excellence in service delivery and protection, continuously enhancing our capabilities to meet and exceed the expectations of those we serve.

The Agency continuously develops and upgrades its systematic security approach through personnel orientations and training, upgrade of physical and electronic means of prevention and detection, as well as surveillance and protective security systems — ensuring continuity of total efficient and quality service to clients.

**Key Practices:**

- Physical Security Measures
- Security Management Planning
- Contract Management
- Asset Identification
- Control and Monitoring
- Uniformed Guard Services

---

### Footer

**Head Office:** Unit 3008, 30th Floor, Jollibee Plaza, F. Ortigas Jr. Road, San Antonio, Pasig City

**Branch Offices:**
- Suite 92, West Gate Suites, Bldg. 286, Sampson Road, Central District Office, Subic Bay Freeport Zone
- J.R. Borja Extension (Great Spot Arcade), Camaman-an, Cagayan de Oro City

**Email:** annapolis082018@gmail.com  
**Phone:** 0917-175-6872

---

## Constraints

- Use the existing Vue.js + Vite project structure
- Keep all styling scoped or in `src/assets/`
- Do not add external UI libraries unless explicitly asked
- Prioritize readability and semantic HTML
