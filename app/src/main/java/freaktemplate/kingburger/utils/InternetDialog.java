package freaktemplate.kingburger.utils;

import android.app.Dialog;
import android.content.Context;
import android.graphics.drawable.ColorDrawable;
import androidx.annotation.NonNull;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;

public class InternetDialog extends Dialog {
    onRetry on_retry;

    public InternetDialog(@NonNull final Context context, final onRetry on_retry) {
        super(context);
        setContentView(R.layout.layout_no_internet);
        setCancelable(false);
        getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));

        TextView tv_tittle = findViewById(R.id.tv_tittle);
        TextView tv_detail = findViewById(R.id.tv_detail);
        Button buttonCancel = findViewById(R.id.buttonCancel);
        Button buttonRetry = findViewById(R.id.buttonRetry);

        tv_tittle.setTypeface(Home.tf_main_bold);
        tv_detail.setTypeface(Home.tf_main_regular);
        buttonCancel.setTypeface(Home.tf_main_bold);
        buttonRetry.setTypeface(Home.tf_main_bold);

        buttonCancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dismiss();
            }
        });

        buttonRetry.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(Home.isNetworkConnected(context)){
                on_retry.onReload();
                dismiss();}
            }
        });
    }
    public interface onRetry{
        void onReload();
    }
}
