
import os
import asyncio
import logging

from dotenv import load_dotenv

from band import Agent
from band.adapters.gemini import GeminiAdapter, GeminiAdapterConfig


# Load environment variables
load_dotenv()

BAND_API_KEY = os.getenv("BAND_API_KEY")
BAND_AGENT_ID = os.getenv("BAND_AGENT_ID")
GEMINI_API_KEY = os.getenv("GEMINI_API_KEY")


# Validate credentials
if not BAND_API_KEY:
    raise ValueError("BAND_API_KEY is missing from .env")

if not BAND_AGENT_ID:
    raise ValueError("BAND_AGENT_ID is missing from .env")

if not GEMINI_API_KEY:
    raise ValueError("GEMINI_API_KEY is missing from .env")


# Logging
logging.basicConfig(
    level=logging.DEBUG,
    format="%(asctime)s | %(levelname)s | %(name)s | %(message)s"
)


async def main():

    # Configure Gemini
    adapter_config = GeminiAdapterConfig(
        model="gemini-3.7-flash",
        provider_key=GEMINI_API_KEY,
        custom_section="""
You are TrustGuard Analyst, a cybersecurity analysis agent.

YOUR ROLE:
Analyze messages, URLs, QR-decoded content, phishing attempts,
online scams, fraudulent requests, and social engineering attacks.

ANALYSIS REQUIREMENTS:
1. Identify suspicious indicators.
2. Explain why each indicator is suspicious.
3. Assess potential risks.
4. Give practical safety recommendations.
5. Never claim something is safe without sufficient evidence.

RESPONSE FORMAT:
- Classification
- Suspicious indicators
- Risk explanation
- Safety recommendation

BAND COMMUNICATION:
Use the Band band_send_message tool to publish your final
response in the current room.

Mention the participant who requested the analysis,
using an available participant handle.

Do not assume that generating plain text alone
publishes a response in the Band room.
"""
    )

    # Create Gemini adapter
    adapter = GeminiAdapter(adapter_config)

    # Create Band agent
    agent = Agent.create(
        adapter=adapter,
        agent_id=BAND_AGENT_ID,
        api_key=BAND_API_KEY
    )

    print("TrustGuard Analyst is connecting to Band...")

    # Start agent
    await agent.run()


if __name__ == "__main__":
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        print("\nTrustGuard Analyst stopped.")
