/*
 Pi_Serial_test.cpp - SerialProtocol library - demo
 Copyright (c) 2014 NicoHood.  All right reserved.
 Program to test serial communication
 
 Compile with:
 sudo gcc -o Pi_Serial_Test.o Pi_Serial_Test.cpp -lwiringPi -DRaspberryPi -pedantic -Wall
 sudo ./Pi_Serial_Test.o
 */
 
// just that the Arduino IDE doesnt compile these files.
 
//include system librarys
#include <stdio.h> //for printf
#include <stdint.h> //uint8_t definitions
#include <stdlib.h> //for exit(int);
#include <string.h> //for errno
#include <errno.h> //error output
#include <pthread.h>
#include <unistd.h>
#include <mysql.h>
#include <fcntl.h>
//wiring Pi
#include <wiringPi.h>
#include <wiringSerial.h>
#include <softTone.h>
//for push alarm
#include <netdb.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <sys/wait.h>
#include <signal.h>
#include <netinet/in.h>
#include <assert.h>
#include <arpa/inet.h>

#define DB_HOST "localhost"
#define DB_USER "root"
#define DB_PASS "root"
#define DB_NAME "adi"
#define DANGEROUS_TEMP 30.0
#define DANGEROUS_BEAT 100

#define GOOD	1
#define SOSO	2
#define BAD	2
#define BUTTON	12
#define MOTER	21
#define SPEAKER 25

int patient_state=GOOD;
char *patient_name = "patient1";

// Find Serial device on Raspberry with ~ls /dev/tty*
// ARDUINO_UNO "/dev/ttyACM0"
// FTDI_PROGRAMMER "/dev/ttyUSB0"
// HARDWARE_UART "/dev/ttyAMA0"
char device[]= "/dev/ttyACM0";
// filedescriptor
int fd;
unsigned long baud = 115200;
unsigned long Atime=0;

//sensing data
int heart1, heart2, heart3, sound, checkSound;
double weight;
bool w_alarm;
int dbtemp, temp;
float ex[3];

//prototypes
void setup(void);
 
void setup(){
  printf("%s \n", "Raspberry Startup!");
  fflush(stdout);
 
  //get filedescriptor
  if ((fd = serialOpen (device, baud)) < 0){
    fprintf (stderr, "Unable to open serial device: %s\n", strerror (errno)) ;
    exit(1); //error
  }
 
  //setup GPIO in wiringPi mode
  if (wiringPiSetupGpio() == -1){
    fprintf (stdout, "Unable to start wiringPi: %s\n", strerror (errno)) ;
    exit(1); //error
  }

}
   

void *sensing(void *arg){
	char buf[200], flushBuf[200]={0, };
	char *ptr;
	char bufStr[20];
	int i = 0;

//	setup();
	pinMode(BUTTON, INPUT);
	pinMode(SPEAKER, OUTPUT);
	softToneCreate(SPEAKER);
	while(1){
		//button
		softToneWrite(SPEAKER, 0);
		if(digitalRead(BUTTON) == HIGH){
			softToneWrite(SPEAKER, 440);
			delay(200);
			softToneWrite(SPEAKER, 220);
			delay(200);
		}
	
	// Pong every 3 seconds
	  if(millis()-Atime>=3000){
	    serialPuts (fd, "Pong!\n");
	    // you can also write data from 0-255
	    // 65 is in ASCII 'A'
	    serialPutchar (fd, 65);
	    Atime=millis();
	  }
 	 // read signal
 	 if(serialDataAvail (fd)){
 	   //char newChar = serialGetchar (fd);
 	   //printf("%c", newChar);
	
		buf[i++] = serialGetchar(fd);
		if(i>60){
			ptr = strchr(buf, '!');
			strncpy(bufStr,ptr+1,3);
			if(bufStr[2]==',')
				bufStr[2]='\n';
			else bufStr[3]='\n';
			heart1 = atoi(bufStr);

			ptr = strchr(buf, '@');
			strncpy(bufStr,ptr+2,3);
			bufStr[3]='\n';
			weight = atof(bufStr);

			ptr = strchr(buf, '%');
			strncpy(bufStr,ptr+1,2);
			bufStr[2]='\n';
			temp = atoi(bufStr);
			
			printf("heart : %d , weight : %lf, temp : %d\n",heart1, weight, temp);
			
			strcpy(buf, flushBuf);
			i = 0;
		}
	    //fgets(bufStr, sizof(bufStr),stdout);
  	  fflush(stdout);
	  }
	}	
}
 
MYSQL* mysql_connection_setup() {
 
    MYSQL *connection = mysql_init(NULL);
 
    if(!mysql_real_connect(connection, DB_HOST, DB_USER, DB_PASS, DB_NAME, 0, NULL, 0)) {
 
        printf("Connection error : %s\n", mysql_error(connection));
        exit(1);
 
    }
    return connection;
}

MYSQL_RES* mysql_perform_query(MYSQL *connection, char *sql_query) {
 
    if(mysql_query(connection, sql_query)) {
 
        printf("MYSQL query error : %s\n", mysql_error(connection));
        exit(1);
 
    }
    return mysql_use_result(connection);
}



// main function for normal c++ programs on Raspberry
int main(void){
	MYSQL *conn;
    	MYSQL_RES *res;
	MYSQL_RES *res2;
	MYSQL_RES *res3;
	MYSQL_ROW row;
	char query[256];
	time_t now;
	int cnt=0, num=0;
	bool alarm = true, ac = false;
	int flag;
	setup();
	conn = mysql_connection_setup();

	pinMode(MOTER, OUTPUT);
	res = mysql_perform_query(conn, "show tables");
	while((row = mysql_fetch_row(res)) != NULL)
        printf("%s\n", row[0]);


	pthread_t sensor_thread;
	if (pthread_create(&sensor_thread, NULL, &sensing, NULL))

		printf("thread create failed!!\n");

	while(1){
		sleep(3);
		time(&now);
		printf("time \n");
		printf("%lf \n",weight);

		sprintf(query,"insert into %s (time, heartbeat,temperature,w_alarm, weight) value (%d,%d,%d,%d,%lf);",patient_name, now,heart1,temp, w_alarm, weight);
		printf("%lf \n",weight);
		printf("%s\n",query);
		res = mysql_perform_query(conn, query);

		sprintf(query,"update Alarm set w_alarm=%d where patient_name='%s'",ac, patient_name);
		printf("%s\n",query);
		res = mysql_perform_query(conn, query);

		sprintf(query, "SELECT * FROM room WHERE number='%d'", 0);
		res3 = mysql_perform_query(conn, query);
		row=mysql_fetch_row(res3);
		dbtemp=atoi(row[1]);
		printf("dbtemp = %d\n", dbtemp);
			

		if(dbtemp < temp){
			printf("mmmmmmmmmo\n");
			digitalWrite(MOTER, HIGH);
		}
		else digitalWrite(MOTER, LOW);
/*
		temp = 3;
		sprintf(query, "UPDATE room SET temperature=%d WHERE number=1", temp);
		res3 = mysql_perform_query(conn, query);

		printf("%s\n", query);*/
		//		if(nurse == SOSO){
//				printf("Thank you nurse!!\n");
//				patient_state=SOSO;
//		}
		mysql_free_result(res3);
		cnt=0;
		sprintf(query, "select * from %s order by time DESC limit 3", patient_name);
		res2 = mysql_perform_query(conn, query);
		printf("%s\n", query);
		int j=0;
		while((row = mysql_fetch_row(res2)) != NULL){
				if(atoi(row[1])>DANGEROUS_BEAT)
					cnt++;
				ex[j]=atof(row[4]);	
				j++;
		}
		if(ex[0]<ex[1]-5){
			ac = true;
			num=0;
		}
		if(cnt==3){
			if(patient_state==GOOD){
					printf("Alarm!!\n");
					patient_state=BAD;	

			}
		}
		else{
				printf("I'm good\n");
				patient_state=GOOD;
		}
		sprintf(query, "update Alarm set state='%d' where patient_name='%s'", patient_state, patient_name);
		mysql_perform_query(conn, query);
		num++;
		if(num>3000) ac = false;
	//	printf("%s\n", query);
	}

	sleep(1000000);
  return 0;
}
 
