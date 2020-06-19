package freaktemplate.kingburger.utils;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import androidx.annotation.NonNull;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.apache.commons.lang3.text.WordUtils;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;


public class AlreadyRegisterDialog extends Dialog {
    private String phoneNumber = "";
    private ProgressDialog dialog;


    public AlreadyRegisterDialog(@NonNull final Context context, final int themeResId, String string) {
        super(context, themeResId);
        dialog = new ProgressDialog(context);
        setContentView(R.layout.activity_already_register_dialog);

        if (null != getWindow())
            getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));


        TextView txt_register = findViewById(R.id.txt_register);
        TextView edt_mobileNumber = findViewById(R.id.edt_mobileNumber);
        TextView txt_already_register = findViewById(R.id.txt_already_register);
        String name = SPmanager.getPreference(context, "name");
//        WordUtils.capitalizeFully(name);
        edt_mobileNumber.setText(WordUtils.capitalizeFully(name));
        if (string.equals("login")) {
            txt_already_register.setText(context.getString(R.string.alreadylogin_text));
            txt_register.setText(context.getString(R.string.login));
        }

        Button btn_cancel = findViewById(R.id.buttonCancel);
        Button btn_close = findViewById(R.id.btn_close);


//        edt_mobileNumber.setText(context.getSharedPreferences(context.getString(R.string.shared_pref_name), MODE_PRIVATE).getString("registeredNumber", ""));

        btn_close.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                SPmanager.saveValue(context, "user_id", "null");
                SPmanager.saveValue(context, "name", "null");
                SPmanager.saveValue(context, "phone", "null");
                dismiss();
                Toast.makeText(context, R.string.reg_logout, Toast.LENGTH_SHORT).show();
                Intent intent = new Intent(context, Home.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                context.startActivity(intent);

            }
        });

        btn_cancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                dismiss();
            }
        });
    }
}
