# Digital TrustGuard

**AI-Powered Digital Scam Detection and Trust Analysis Platform**

Digital TrustGuard is a cybersecurity web application designed to help users identify potentially fraudulent messages, suspicious website URLs and malicious QR-code links.

It combines rule-based cybersecurity analysis with AI-generated explanations and Hindsight memory to provide understandable security insights.

## Problem Statement

Online scams frequently use fake delivery notifications, phishing websites, fraudulent payment requests and malicious QR codes. Many users struggle to distinguish legitimate digital communications from scams.

Digital TrustGuard aims to make initial scam assessment easier by providing trust scores, risk classifications, supporting evidence and actionable safety recommendations.

## Features

- **Text and Message Analysis:** Analyse suspicious messages for potential scam indicators.
- **Website URL Analysis:** Examine URLs for suspicious structural characteristics.
- **QR Code Analysis:** Detect URLs embedded in QR codes and analyse the decoded links.
- **Trust Score:** Generate a score from 0–100 using the application's rule-based trust engine.
- **Risk Classification:** Display risk levels and classifications.
- **Evidence Analysis:** Present indicators supporting the assessment.
- **AI-Powered Explanation:** Generate additional cybersecurity analysis using an LLM.
- **Hindsight Memory Integration:** Retrieve relevant previous cybersecurity knowledge and retain successful analysis findings.
- **Analysis History:** Store and review previous analyses using MySQL.

**Important:** Trust scores are automated assessments, not guarantees that a website or message is safe.

## Technology Stack

| Technology | Purpose |
|---|---|
| HTML, CSS, JavaScript | Frontend |
| Bootstrap 5 | Responsive interface |
| PHP | Backend and analysis APIs |
| MySQL | Analysis history and evidence storage |
| Python | AI service |
| FastAPI | Python API |
| OpenRouter | LLM access |
| Hindsight by Vectorize | Long-term memory retrieval and retention |
| XAMPP | Local development environment |

## System Architecture

1. The user submits a message, URL or QR code.
2. The PHP backend processes the input.
3. The TrustGuard analysis engine evaluates suspicious indicators and calculates a trust score.
4. The analysis is stored in the MySQL database.
5. The Python AI service uses OpenRouter to generate an additional cybersecurity explanation.
6. Hindsight retrieves relevant previous knowledge and retains successful analysis findings.
7. Results are presented through the TrustGuard interface.

The rule-based trust score and the supplementary AI explanation are separate parts of the application.

## Hindsight Memory Workflow

Digital TrustGuard integrates Hindsight to provide memory across cybersecurity analysis interactions.

**1. RETAIN**

After a successful AI analysis, the application sends the submitted content and analysis findings to the Hindsight memory bank named `trustguard-memory`.

**2. RECALL**

For a subsequent analysis, the Python backend queries Hindsight for relevant previous cybersecurity knowledge.

**3. USE**

Retrieved memory is supplied as additional context to the AI model to help inform its subsequent analysis.

The intended benefit is to make relevant knowledge from previous interactions available during later cybersecurity assessments.

## Project Structure

```text
trustguard/
│
├── api/
│   ├── analysis_details.php
│   ├── analyze.php
│   ├── history.php
│   ├── scam_analyzer.php
│   ├── trust_engine.php
│   ├── url_analyzer.php
│   └── url_api.php
│
├── config/
│   └── database.example.php
│
├── css/
│   └── style.css
│
├── database/
│   └── schema.sql
│
├── trustguard-ai/
│   ├── app.py
│   ├── band_agent.py
│   ├── config.py
│   └── requirements.txt
│
├── index.php
├── index.html
├── analysis.html
├── history.html
└── README.md
```

## Installation and Setup

### Prerequisites

- Windows
- XAMPP with Apache and MySQL
- Python 3.14 or a compatible Python environment
- Git
- OpenRouter API key
- Hindsight API key

### 1. Clone the Repository

```bash
git clone YOUR_GITHUB_REPOSITORY_URL
cd trustguard
```

### 2. Configure MySQL

1. Start Apache and MySQL through XAMPP.
2. Open `http://localhost/phpmyadmin`.
3. Import `database/schema.sql`.
4. Copy `config/database.example.php` to `config/database.php`.
5. Update the database credentials in `config/database.php`.

### 3. Configure the Python AI Service

Navigate to the Python backend:

```bash
cd trustguard-ai
```

Create a virtual environment:

```bash
python -m venv venv
```

Install dependencies:

```bash
venv\Scripts\python.exe -m pip install -r requirements.txt
```

Create a `.env` file in the `trustguard-ai` directory and add:

```env
OPENROUTER_API_KEY=YOUR_OPENROUTER_API_KEY
HINDSIGHT_API_KEY=YOUR_HINDSIGHT_API_KEY
```

Never upload the `.env` file or share API keys publicly.

### 4. Start the Python AI Service

From the `trustguard-ai` directory:

```bash
venv\Scripts\python.exe -m uvicorn app:app --host 127.0.0.1 --port 8000
```

The Python service should be accessible at:

```text
http://127.0.0.1:8000/
```

### 5. Open Digital TrustGuard

Start Apache and MySQL in XAMPP.

Open the application in your browser:

```text
http://localhost/trustguard/index.php
```

Internet access is required for the external OpenRouter and Hindsight services.

## Database Structure

The application uses two primary tables:

**analyses**
- id
- input_type
- input_content
- trust_score
- risk_level
- classification
- recommendation
- created_at

**evidence**
- id
- analysis_id
- category
- result
- explanation
- severity
- created_at

Refer to `database/schema.sql` for the database creation script.

## Limitations

- Automated analysis may produce false positives or false negatives.
- URL checks do not guarantee that a live website is safe.
- QR analysis focuses on decoded URL content.
- AI explanations depend on external model availability and API limits.
- Hindsight retrieval and memory availability depend on its external service.

Users should independently verify suspicious communications and avoid sharing passwords, OTPs or payment credentials.

## Hackathon Project

**Project:** Digital TrustGuard

**Domain:** Cybersecurity / Artificial Intelligence

**Core focus:** AI-assisted scam detection, digital trust assessment and memory-enhanced cybersecurity analysis.

## Demo

Demo video: To be added.

Screenshots: To be added.

## License

License to be decided by the project contributors.