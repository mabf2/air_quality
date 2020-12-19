#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>

#define SEALEVELPRESSURE_HPA (1013.25)

Adafruit_BME280 bme;

/* Configuração da conexão do Wi-Fi (ssid e password) */
const char* ssid     = "xxxxxxxxxx";
const char* password = "xxxxxxxxxxxxxxxx";

/* Configurar o valor do intervalo de atualização em minutos (timerDelay) */
unsigned long timerDelay = 1;
unsigned long lastTime = 0;

float temperature, humidity, pressure, altitude;
int ledPin = 13;

/* Configurar URL e caminho dos arquivos PHP (serverInput e serverInfo) */
String serverInput = "http://xxx.xxx.xxx.xxx/aqs_esp/aqs-post-data.php";
String serverInfo  = "http://xxx.xxx.xxx.xxx/aqs_esp/aqs-return-info.php";
/* 
 *  Configurar chave de acesso ao PHP (PostKeyValue) 
 *  Mesma chave cadastrada no arquivo 'aqs-database.php'
*/
String PostKeyValue    = "rGSpY4jBk1vRz";

String DateTimeServer = "";
String LocationServer = "";
String WiFiStatus     = "D";
String WiFimacAddress = "";
String information    = "{}";
 
void setup() {
  Serial.begin(115200);
  pinMode(ledPin,OUTPUT);
  digitalWrite(ledPin,LOW);
  delay(500);
  
  bme.begin(0x76);   

  connect_to_wifi();

  timerDelay = timerDelay * 1000 * 60;
  lastTime = millis() - timerDelay;
  digitalWrite( ledPin , HIGH);
}

void loop() {
  if ((millis() - lastTime) > timerDelay) {
    readSensors();    
    lastTime = millis();
  }
}

void connect_to_wifi() {
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  
  Serial.println("");
  Serial.println("WiFi connected..!");
  Serial.print("Got IP: ");  Serial.println(WiFi.localIP());
  Serial.print("MAC address: ");
  Serial.println(WiFi.macAddress() );
  WiFimacAddress = WiFi.macAddress();
  WiFimacAddress.replace(":", "");
}

void readSensors(){
  float TempBMP, AlttBMP, PresBMP, TempDHT, UmidDHT;

  if (WiFi.status() == WL_CONNECTED){
    if(WiFiStatus == "D"){
      WiFiStatus = "C";
      digitalWrite( ledPin , HIGH);
    }
    temperature = bme.readTemperature();
    humidity    = bme.readHumidity();
    pressure    = bme.readPressure() / 100.0F;
    altitude    = bme.readAltitude(SEALEVELPRESSURE_HPA);
      
    if ( isnan(temperature) || isnan(humidity) || isnan(pressure) || isnan(altitude) ) {
      Serial.println("Erro ao ler sensor BME280!");
    } else {
      input_data("BME280", "T", String((int)temperature));
      input_data("BME280", "A", String((int)altitude));
      input_data("BME280", "P", String((int)pressure));
      input_data("BME280", "H", String((int)humidity));
    }
    
  }else {
    if(WiFiStatus == "C"){
      WiFiStatus = "D";
      digitalWrite(ledPin,LOW);
    }
    Serial.println("WiFi Disconnected");
    connect_to_wifi();
  }
}

void input_data(String strSensor, String strFeature, String strValue){
  digitalWrite( ledPin , LOW);
  sendToMySQL(strSensor, strFeature, strValue);
  digitalWrite( ledPin , HIGH);
}

void sendToMySQL(String strSensor, String strFeature, String strValue){
  Serial.println("MySQL");
  HTTPClient http;
  http.begin(serverInput);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  String httpRequestData = "api_key="  + apiKeyValue + "&macaddress=" + WiFimacAddress +
                           "&sensor="  + strSensor   + "&feature="    + strFeature     +
                           "&value="   + strValue;
  Serial.print("HTTP Request Data: ");
  Serial.println(httpRequestData);

  int httpResponseCode = http.POST(httpRequestData);
  if (httpResponseCode > 0) {
    Serial.print("HTTP Response Code: ");
    Serial.println(httpResponseCode);
  } else {
    Serial.print("Error Code: ");
    Serial.println(httpResponseCode);
  }
  http.end();
  delay(20);
}
