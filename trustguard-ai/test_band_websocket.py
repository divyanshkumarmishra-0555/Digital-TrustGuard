
import asyncio
import os

from dotenv import load_dotenv
from websockets.asyncio.client import connect
from websockets.exceptions import InvalidStatus

load_dotenv()

API_KEY = os.getenv("BAND_API_KEY")

async def main():
    if not API_KEY:
        print("BAND_API_KEY is missing.")
        return

    url = "wss://app.band.ai/api/v1/socket/websocket?vsn=2.0.0"

    try:
        async with connect(
            url,
            additional_headers={"x-api-key": API_KEY},
            open_timeout=15,
        ):
            print("SUCCESS: WebSocket handshake completed.")

    except InvalidStatus as error:
        print("WebSocket HTTP status:", error.response.status_code)

    except Exception as error:
        print("Connection error type:", type(error).__name__)
        print("Connection error:", str(error).replace(API_KEY, "[REDACTED]"))

asyncio.run(main())
