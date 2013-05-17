package com.wireflydesign.plugin.ticket.scanbarcode;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.EOFException;
import java.io.IOException;
import java.util.ArrayList;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.media.Ringtone;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.TextView;
import biz.tekeye.scanbarcode.R;

public class Main extends Activity {
	public String[] s;
	String filename = "ticket.dat";
	ArrayList<String> ar = new ArrayList<String>();

	  @Override
	  public void onCreate(Bundle savedInstanceState) {

		  try {
			  // Read in all tickets
			  Log.i("KIM", "READING IN ALL TICKETS -----------");
			  DataInputStream in = new DataInputStream(openFileInput(filename));
			    try {
			        for (;;) {
			        	String str = in.readUTF();
			          Log.i("GOT DATA : ", str);
			          ar.add(str);
			        }
			    } catch (EOFException e) {
			        Log.i("Data Input Ticket", "End of file reached");
			    }
			    in.close();
			} catch (IOException e) {
			    Log.i("Data Input Ticket", "I/O Error");
			}		  
		  
		  
		  
	    super.onCreate(savedInstanceState);
	    setContentView(R.layout.main);
	    HandleClick hc = new HandleClick();
	    findViewById(R.id.butQR).setOnClickListener(hc);
	    findViewById(R.id.butProd).setOnClickListener(hc);
	    findViewById(R.id.butOther).setOnClickListener(hc);
	  }
	  
	  private class HandleClick implements OnClickListener{
	    public void onClick(View arg0) {
	      Intent intent = new Intent("com.google.zxing.client.android.SCAN");
	      switch(arg0.getId()){
	        case R.id.butQR:
	          intent.putExtra("SCAN_MODE", "QR_CODE_MODE");
	        break;
	        case R.id.butProd:
	        	// Reset the ticket file
	        	Log.i("KIM", "RESETTING ALL TICKETS -----------");
	        	ar.clear();
	        	try {
	        		// Write a String
	        		DataOutputStream out = new DataOutputStream(openFileOutput(filename, Context.MODE_PRIVATE));
	        		out.close();
	        	} catch (IOException e) {
	        		Log.i("Data Reset Ticket", "I/O Error");
	        	}
	          //intent.putExtra("SCAN_MODE", "PRODUCT_MODE");
	        break;
	        case R.id.butOther:
	          //intent.putExtra("SCAN_FORMATS", "CODE_39,CODE_93,CODE_128,DATA_MATRIX,ITF");
	        break;
	      }
	      startActivityForResult(intent, 0);    //Barcode Scanner to scan for us
	    }
	  }
	  
	  public void onActivityResult(int requestCode, int resultCode, Intent intent) {
	    if (requestCode == 0) {
	      TextView tvStatus=(TextView)findViewById(R.id.tvStatus);
	      TextView tvResult=(TextView)findViewById(R.id.tvResult);
	      if (resultCode == RESULT_OK) {

	        tvStatus.setText(intent.getStringExtra("SCAN_RESULT_FORMAT"));
	        tvResult.setText(intent.getStringExtra("SCAN_RESULT"));

if (ar.indexOf(intent.getStringExtra("SCAN_RESULT")) == -1)
{
	ar.add(intent.getStringExtra("SCAN_RESULT"));
	tvResult.setText("Ticket OK");
	tvResult.setBackgroundColor(0xFF00FF00 );

	// Write out the ticket
	Log.i("KIM", "WRITING OUT THIS TICKET -----------");
	try {
		// Write a String
		DataOutputStream out = new DataOutputStream(openFileOutput(filename, Context.MODE_APPEND));
		out.writeUTF(intent.getStringExtra("SCAN_RESULT"));
		//out.writeUTF("My Word!".toString());
		out.close();
	} catch (IOException e) {
		Log.i("Data Write Ticket", "I/O Error");
	}
	
	// Beep!
    try {
      Uri notification = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
      Ringtone r = RingtoneManager.getRingtone(getApplicationContext(), notification);
         r.play();
    } catch (Exception e) {}
}
else
{
	tvResult.setText("Duplicate ticket");
	tvResult.setBackgroundColor(Color.RED);
	
	/*
	// Beep!
    try {
      Uri notification = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_ALARM);
      Ringtone r = RingtoneManager.getRingtone(getApplicationContext(), notification);
         r.play();
    } catch (Exception e) {}
    */
}

//int size = ar.size();
//tvResult.setText(Integer.toString(size));

	      } else if (resultCode == RESULT_CANCELED) {
	        tvStatus.setText("Press a button to start a scan.");
	        tvResult.setText("Scan cancelled.");
	        
	      }
	    }
	  }
	  
	  // These next two methods handle displayed content when screen is tilted
	  @Override
	  public void onSaveInstanceState(Bundle outState) {
	  super.onSaveInstanceState(outState);
	  outState.putString("SCAN_RESULT", (String) ((TextView)findViewById(R.id.tvResult)).getText());
	  }
	  @Override
	  public void onRestoreInstanceState(Bundle savedInstanceState) {
	  super.onRestoreInstanceState(savedInstanceState);
	  ((TextView)findViewById(R.id.tvResult)).setText(savedInstanceState.getString("SCAN_RESULT"));
	  }
	
	}