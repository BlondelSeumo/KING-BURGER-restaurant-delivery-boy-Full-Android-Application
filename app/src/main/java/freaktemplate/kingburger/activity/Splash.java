package freaktemplate.kingburger.activity;

import android.content.Intent;
import android.os.Handler;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.ImageView;

import com.bumptech.glide.Glide;
import com.crashlytics.android.Crashlytics;
import freaktemplate.kingburger.R;
import freaktemplate.kingburger.utils.LanguageSelectore;
import io.fabric.sdk.android.Fabric;

public class Splash extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Fabric.with(this, new Crashlytics());
        setContentView(R.layout.activity_splash);
        LanguageSelectore.setLanguageIfAR( Splash.this );
        int SPLASH_DISPLAY_LENGTH = 3000;
        ImageView imgGif = findViewById(R.id.imgGif);
        Glide.with(Splash.this).load(R.drawable.ezgif).into(imgGif);

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                Intent mainIntent = new Intent(Splash.this, Home.class);
                startActivity(mainIntent);
                finish();
            }
        }, SPLASH_DISPLAY_LENGTH);
    }
}
