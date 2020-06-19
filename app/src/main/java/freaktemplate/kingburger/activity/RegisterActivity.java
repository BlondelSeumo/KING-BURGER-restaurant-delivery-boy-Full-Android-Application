package freaktemplate.kingburger.activity;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.util.Patterns;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.androidnetworking.AndroidNetworking;
import com.androidnetworking.common.Priority;
import com.androidnetworking.error.ANError;
import com.androidnetworking.interceptors.GzipRequestInterceptor;
import com.androidnetworking.interfaces.StringRequestListener;
import com.bumptech.glide.Glide;

import org.json.JSONException;
import org.json.JSONObject;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.utils.AlreadyRegisterDialog;
import freaktemplate.kingburger.utils.SPmanager;
import okhttp3.OkHttpClient;

public class RegisterActivity extends AppCompatActivity {
    private static final String TAG = "RegisterActivity";
    private ImageView imgGif;
    private TextView txt_login, txt_register;
    private EditText et_password, et_email, et_cpassword, et_name, et_mobileNumber;
    private Context context;
    private ProgressDialog progressDialog;
    private OkHttpClient okHttpClient;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        context = RegisterActivity.this;
        okHttpClient = new OkHttpClient().newBuilder()
                .addInterceptor(new GzipRequestInterceptor())
                .build();
        initlise();
    }

    private int getImage() {
        return this.getResources().getIdentifier("ezgif", "drawable", this.getPackageName());
    }

    private void initlise() {
        imgGif = findViewById(R.id.imgGif);
        txt_register = findViewById(R.id.txt_register);
        txt_login = findViewById(R.id.txt_login);
        et_name = findViewById(R.id.et_name);
        et_mobileNumber = findViewById(R.id.et_mobileNumber);
        et_password = findViewById(R.id.et_password);
        et_cpassword = findViewById(R.id.et_cpassword);
        et_email = findViewById(R.id.et_email);

        Glide.with(this).load(getImage()).into(imgGif);
        txt_register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                validation();
            }
        });
    }

    private static boolean isValidEmail(String email) {
        return !TextUtils.isEmpty(email) && Patterns.EMAIL_ADDRESS.matcher(email).matches();
    }

    private void validation() {
        String mobile = et_mobileNumber.getText().toString().trim();
        String password = et_password.getText().toString().trim();
        String cpassword = et_cpassword.getText().toString().trim();
        String name = et_name.getText().toString().trim();
        String email = et_email.getText().toString().trim();
        if (!name.isEmpty()) {
           /* if (isValidEmail(email)) {*/
                if (!mobile.isEmpty()) {
                    if (!password.isEmpty()) {
                        if (cpassword.equals(password)) {
                            register(mobile, password, name, email);
                        } else {
                            if (cpassword.isEmpty()) {
                                et_cpassword.setError("Enter confirm password");
                            } else {
                                et_cpassword.setError("password missmatch");
                                et_cpassword.getParent().requestChildFocus(et_cpassword, et_cpassword);

                            }
                        }

                    } else {
                        et_password.setError("Enter password");
                        et_password.getParent().requestChildFocus(et_password, et_password);

                    }

                } else {
                    et_mobileNumber.setError("Enter mobile");
                    et_mobileNumber.getParent().requestChildFocus(et_mobileNumber, et_mobileNumber);
                }
            /*} else {
                if (email.isEmpty()) {
                    et_email.setError(getString(R.string.enter_email));
                } else {
                    et_email.setError(getString(R.string.ente_valid_email));

                }*/


        } else {
            et_name.setError("Enter name");
            et_name.getParent().requestChildFocus(et_name, et_name);

        }
    }

    private void register(String mobile, String password, String name, String email) {
        String token = SPmanager.getPreference(context, "token");
        progressDialog = new ProgressDialog(context);
        progressDialog.setMessage(getString(R.string.loading));
        progressDialog.setCancelable(true);
        progressDialog.show();
        String url = getString(R.string.link) + "api/register";
        Log.e(TAG, "register: "+url );
        AndroidNetworking.get(url)
                .addQueryParameter("mobile_no", mobile)
                .addQueryParameter("token", token)
                .addQueryParameter("name", name)
                .addQueryParameter("password", password)
                .addQueryParameter("type", "2")
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
                                String phone = register.getString("phone");
                                SPmanager.saveValue(context, "user_id", user_id);
                                SPmanager.saveValue(context, "name", name);
                                SPmanager.saveValue(context, "phone", phone);
                                progressDialog.dismiss();
                                Toast.makeText(context, getString(R.string.reg_sucess), Toast.LENGTH_SHORT).show();
                                Intent intent = new Intent(context, Home.class);
                                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                startActivity(intent);
                            } else {
                                progressDialog.dismiss();
//                                alreadyRegister(true);
                                Toast.makeText(context, getString(R.string.allready), Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            Log.e(TAG, "onResponse: " + e.getMessage());
                            e.printStackTrace();
                            progressDialog.dismiss();
                            Toast.makeText(context, getString(R.string.retry), Toast.LENGTH_SHORT).show();
                        }

                    }

                    @Override
                    public void onError(ANError anError) {
                        Log.e(TAG, "onError: " + anError.getErrorDetail());
                        Log.e(TAG, "onError: " + anError.getErrorBody());
                        progressDialog.dismiss();
                        Toast.makeText(context, getString(R.string.retry), Toast.LENGTH_SHORT).show();
                    }
                });
    }

    private void alreadyRegister(boolean isOpen) {
        AlreadyRegisterDialog alreadyRegisterDialog = new AlreadyRegisterDialog(context, android.R.style.Theme_NoTitleBar_Fullscreen, "register");
        alreadyRegisterDialog.setCancelable(isOpen);
        alreadyRegisterDialog.show();
    }

}
