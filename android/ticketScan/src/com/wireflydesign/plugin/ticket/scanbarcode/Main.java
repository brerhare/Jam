package com.wireflydesign.plugin.ticket.scanbarcode;

import java.util.ArrayList;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.media.Ringtone;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.TextView;
import biz.tekeye.scanbarcode.R;

public class Main extends Activity {
	public String[] s;
	ArrayList<String> ar = new ArrayList<String>();
	
	  @Override
	  public void onCreate(Bundle savedInstanceState) {
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
	          intent.putExtra("SCAN_MODE", "PRODUCT_MODE");
	        break;
	        case R.id.butOther:
	          intent.putExtra("SCAN_FORMATS", "CODE_39,CODE_93,CODE_128,DATA_MATRIX,ITF");
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