
import os
from dotenv import load_dotenv
from hindsight_client import Hindsight

# Load credentials from .env
load_dotenv()

api_key = os.getenv("HINDSIGHT_API_KEY")

if not api_key:
    raise ValueError("Hindsight API key is missing.")

# Connect to Hindsight Cloud
client = Hindsight(
    base_url="https://api.hindsight.vectorize.io",
    api_key=api_key
)

try:
    bank = client.create_bank(
        bank_id="trustguard-memory",
        name="TrustGuard Memory"
    )

    print("SUCCESS: Connected to Hindsight!")
    print("Memory bank:", bank.bank_id)

finally:
    client.close()
