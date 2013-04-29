package com.wireflydesign.plugin.ticket.scanbarcode;

import biz.tekeye.scanbarcode.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.TextView;

public class Main extends Activity {
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
	      } else if (resultCode == RESULT_CANCELED) {
	        tvStatus.setText("Press a button to start a scan.");
	        tvResult.setText("Scan cancelled.");
	      }
	    }
	  }
	}