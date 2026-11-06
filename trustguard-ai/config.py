import os

BAND_AGENT_ID = os.getenv("a1c0a209-272f-43ec-ac4e-9c1fb402c2c6")
BAND_API_KEY = os.getenv("band_a_1793889765_ko7YKfqAyZYN105HIlChf2CoNYV71aPF")

if not BAND_AGENT_ID or not BAND_API_KEY:
    raise ValueError("Band credentials are not configured.")