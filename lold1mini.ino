#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <GyverBME280.h>

const char* ssid = "door";
const char* password = "12345678";
String url = "http://c92793sz.beget.tech/device_input.php";

// "http://c92793sz.beget.tech/device_input.php?temperature=30&token=5adb506125640027afbc680e3dbc0076"

GyverBME280 bme;

void setup() {
  Serial.begin(9600);
  WiFi.begin(ssid, password);
  bme.begin();
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {

  String t = String(bme.readTemperature());
  String data = url + "?temperature=" + t + "&token=5adb506125640027afbc680e3dbc0076";
  Serial.println(t);
  Serial.println(data);

  WiFiClient client;
  HTTPClient http;
  http.begin(client, data);

  int httpCode = http.GET();
  if (httpCode > 0) {
    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println(payload);
    } else {
      Serial.printf("HTTP request failed with error code: %d\n", httpCode);
    }
  } else {
    Serial.println("Failed to make HTTP request");
  }
  http.end();
  delay(60000);
}