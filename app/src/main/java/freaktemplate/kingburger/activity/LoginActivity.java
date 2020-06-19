package freaktemplate.kingburger.activity;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import android.text.TextUtils;
import android.util.Log;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.androidnetworking.AndroidNetworking;
import com.androidnetworking.common.Priority;
import com.androidnetworking.error.ANError;
import com.androidnetworking.interceptors.GzipRequestInterceptor;
import com.androidnetworking.interfaces.JSONObjectRequestListener;
import com.androidnetworking.interfaces.StringRequestListener;
import com.bumptech.glide.Glide;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import org.json.JSONException;
import org.json.JSONObject;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.getSet.Forgotpojjo;
import freaktemplate.kingburger.utils.SPmanager;
import okhttp3.OkHttpClient;

public class LoginActivity extends AppCompatActivity implements View.OnClickListener {

    private static final String TAG = "LoginActivity";
    private ImageView imgGif;
    private TextView txt_forgot, txt_login, txt_register;
    private EditText et_password, et_mobileNumber;
    private Context context;
    private ProgressDialog progressDialog;
    private OkHttpClient okHttpClient;
    private Dialog dialog;
    private EditText et_email;
    private Gson gson;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        context = LoginActivity.this;
        okHttpClient = new OkHttpClient().newBuilder()
                .addInterceptor(new GzipRequestInterceptor())
                .build();
        GsonBuilder gsonBuilder = new GsonBuilder();
        gson = gsonBuilder.create();
        initlise();

    }

    private void initlise() {
        imgGif = findViewById(R.id.imgGif);
        txt_register = findViewById(R.id.txt_register);
        txt_login = findViewById(R.id.txt_login);
        txt_forgot = findViewById(R.id.txt_forgot);
        et_mobileNumber = findViewById(R.id.et_mobileNumber);
        et_password = findViewById(R.id.et_password);

        Glide.with(this).load(getImage("ezgif")).into(imgGif);
        txt_register.setOnClickListener(this);
        txt_forgot.setOnClickListener(this);
        txt_login.setOnClickListener(this);
    }

    private int getImage(String imageName) {
        int drawableResourceId = this.getResources().getIdentifier(imageName, "drawable", this.getPackageName());

        return drawableResourceId;
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.txt_login:
                validation();
                break;
            case R.id.buttonRegister:
                String email = et_email.getText().toString().trim();
                if (isValidEmail(email)) {
                    forgotpassword(email);
                } else {
                    if (email.isEmpty()) {
                        et_email.setError(getString(R.string.enter_email));
                    } else {
                        et_email.setError(getString(R.string.ente_valid_email));

                    }
                }
                break;
            case R.id.buttonCancel:
                dialog.cancel();
                break;
            case R.id.txt_forgot:
                openForgotdialog();
                break;
            case R.id.txt_register:
                Intent intent = new Intent(LoginActivity.this, RegisterActivity.class);
                startActivity(intent);
                break;
        }
    }

    private void forgotpassword(String email) {
        String url = getString(R.string.link) + "api/forgotpassword?phone=" + email;
        Log.e(TAG, "forgotpassword: " + url);
        progressDialog = new ProgressDialog(context);
        progressDialog.setMessage(getString(R.string.loading));
        progressDialog.setCancelable(true);
        progressDialog.show();

        AndroidNetworking.get(url)
                .setPriority(Priority.LOW)
                .setOkHttpClient(okHttpClient)
                .build()
                .getAsJSONObject(new JSONObjectRequestListener() {
                    @Override
                    public void onResponse(JSONObject response) {
                        progressDialog.dismiss();
                        Log.e(TAG, "onResponse: " + response);
                        try {
                            Forgotpojjo responseObject = gson.fromJson(String.valueOf(response), Forgotpojjo.class);
                            if (responseObject.getSuccess().equals(1)) {
                                Toast.makeText(context, R.string.passsend, Toast.LENGTH_SHORT).show();
                                dialog.dismiss();
                            } else {
                                Toast.makeText(context, R.string.retry, Toast.LENGTH_SHORT).show();
                                dialog.dismiss();


                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }

                    }


                    @Override
                    public void onError(ANError anError) {
                        Log.e(TAG, "onError: " + anError.getErrorDetail());
                        progressDialog.dismiss();
                    }
                });
    }

    private static boolean isValidEmail(String email) {
        return !TextUtils.isEmpty(email) && Patterns.EMAIL_ADDRESS.matcher(email).matches();
    }

    private void openForgotdialog() {
        dialog = new Dialog(context);
        dialog.setContentView(R.layout.forgot_password_dialog);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        dialog.show();
        Button buttonCancel = (Button) dialog.findViewById(R.id.buttonCancel);
        Button buttonRegister = (Button) dialog.findViewById(R.id.buttonRegister);
        et_email = (EditText) dialog.findViewById(R.id.et_email);

        buttonCancel.setOnClickListener(this);
        buttonRegister.setOnClickListener(this);
    }

    private void validation() {
        String mobile = et_mobileNumber.getText().toString().trim();
        String password = et_password.getText().toString().trim();

        if (!mobile.isEmpty()) {
            if (!password.isEmpty()) {
                login(mobile, password);
            } else {
                et_password.setError("Enter password");
            }
        } else {
            et_mobileNumber.setError("Enter mobile");
        }
    }

    private void login(String mobile, String password) {
        String token = SPmanager.getPreference(context, "token");
        progressDialog = new ProgressDialog(context);
        progressDialog.setMessage(getString(R.string.loading));
        progressDialog.setCancelable(true);
        progressDialog.show();
        String url = getString(R.string.link) + "api/register";
        Log.e(TAG, "login: "+url );
        AndroidNetworking.get(url)
                .addQueryParameter("mobile_no", mobile)
                .addQueryParameter("token", token)
                .addQueryParameter("type", "1")
                .addQueryParameter("password", password)
                .setPriority(Priority.MEDIUM)
                .setOkHttpClient(okHttpClient)
                .build()
                .getAsString(new StringRequestListener() {
                    @Override
                    public void onResponse(String response) {
                        Log.e(TAG, "onResponse: " + response);
                        try {
                            JSONObject jsonObject = new JSONObject(response);
                            JSONObject data = jsonObject.getJSONObject("data");
                            String success = data.getString("success");
                            if (success.equals("1")) {
                                JSONObject register = data.getJSONObject("register");
                                String user_id = register.getString("user_id");
                                String name = register.getString("name");
//                                String phone = register.getString("phone");
                                SPmanager.saveValue(context, "user_id", user_id);
                                SPmanager.saveValue(context, "name", name);
//                                SPmanager.saveValue(context, "phone", phone);
                                progressDialog.dismiss();
                                Toast.makeText(context, getString(R.string.login_success), Toast.LENGTH_SHORT).show();
                                Intent intent = new Intent(context, Home.class);
                                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                startActivity(intent);
                            } else {
                                progressDialog.dismiss();
                                Toast.makeText(context, getString(R.string.mobile_password), Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            progressDialog.dismiss();
                            Toast.makeText(context, getString(R.string.retry), Toast.LENGTH_SHORT).show();
                        }

                    }

                    @Override
                    public void onError(ANError anError) {
                        Log.e(TAG, "onError: " + anError.getErrorDetail());
                        progressDialog.dismiss();
                        Toast.makeText(context, getString(R.string.retry), Toast.LENGTH_SHORT).show();
                    }
                });
    }
}
