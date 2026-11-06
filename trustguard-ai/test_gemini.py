
import os
from dotenv import load_dotenv
from google import genai

load_dotenv()

api_key = os.getenv("GEMINI_API_KEY")

if not api_key:
    raise ValueError("Gemini API key is missing.")

client = genai.Client(api_key=api_key)

try:
    models = client.models.list()

    print("Gemini API connection successful!")
    print("\nAvailable Gemini models:")

    for model in models:
        print(model.name)

finally:
    client.close()
