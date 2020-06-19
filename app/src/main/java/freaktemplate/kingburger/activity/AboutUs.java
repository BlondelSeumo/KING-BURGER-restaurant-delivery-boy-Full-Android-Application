package freaktemplate.kingburger.activity;

import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.webkit.WebView;
import android.widget.TextView;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.utils.LanguageSelectore;

public class AboutUs extends AppCompatActivity {

    WebView wv_aboutUs;
    TextView tv_tittle;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LanguageSelectore.setLanguageIfAR( AboutUs.this );
        setContentView(R.layout.activity_about_us);
        wv_aboutUs = findViewById(R.id.wv_aboutUs);
        tv_tittle = findViewById(R.id.tv_tittle);


        wv_aboutUs.loadData(getString(R.string.about_us_dummy_data),"text/html","UTF-8");
    }
}
