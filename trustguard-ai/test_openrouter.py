
import os
from dotenv import load_dotenv
from openai import OpenAI

load_dotenv()

api_key = os.getenv("OPENROUTER_API_KEY")

if not api_key:
    raise ValueError("OPENROUTER_API_KEY is missing from .env")

client = OpenAI(
    base_url="https://openrouter.ai/api/v1",
    api_key=api_key
)

try:
    response = client.chat.completions.create(
        model="openrouter/free",
        messages=[
            {
                "role": "user",
                "content": (
                    "Reply with exactly: "
                    "TRUSTGUARD OPENROUTER AI TEST OK"
                )
            }
        ],
        max_tokens=300
    )

    print("RESPONSE:", response.choices[0].message.content)
    print("MODEL USED:", response.model)
    print("FINISH REASON:", response.choices[0].finish_reason)
except Exception as error:
    print("OPENROUTER TEST FAILED:", str(error))

finally:
    client.close()
