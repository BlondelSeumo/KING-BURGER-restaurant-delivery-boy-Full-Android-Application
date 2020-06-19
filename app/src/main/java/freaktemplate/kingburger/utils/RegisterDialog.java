package freaktemplate.kingburger.utils;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.graphics.drawable.ColorDrawable;
import androidx.annotation.NonNull;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.NetworkResponse;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import static android.content.Context.MODE_PRIVATE;

public class RegisterDialog extends Dialog {
    private String phoneNumber = "";
    private ProgressDialog dialog;
    private String currentNumber  ;


    public RegisterDialog(@NonNull final Context context, final int themeResId) {
        super(context, themeResId);
        dialog = new ProgressDialog(context);
        setContentView(R.layout.layout_register_dialog);


        if (null != getWindow())
            getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));

        //initialize Views

        TextView tv_register = findViewById(R.id.tv_register);
        TextView tv_register_detail = findViewById(R.id.tv_register_detail);
        final EditText et_mobileNumber = findViewById(R.id.et_mobileNumber);
        Button buttonRegister = findViewById(R.id.buttonRegister);
        Button buttonCancel = findViewById(R.id.buttonCancel);

        //setting TypeFace

        tv_register.setTypeface(Home.tf_main_bold);
        tv_register_detail.setTypeface(Home.tf_main_regular);
        et_mobileNumber.setTypeface(Home.tf_main_bold);
        buttonRegister.setTypeface(Home.tf_main_bold);

        buttonRegister.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (isValid(et_mobileNumber)) {

                    if (Home.isNetworkConnected(context)) {
                        registerToServer(context);
                    }

                    dismiss();
                } else {
                    et_mobileNumber.setError(context.getString(R.string.enter_mobile_number));
                }
            }
        });
        buttonCancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                context.getSharedPreferences(context.getString(R.string.shared_pref_name), MODE_PRIVATE).edit().putBoolean("isFirstTimeRegister", false).apply();
                dismiss();
            }
        });
    }

    private void registerToServer(final Context context) {
        //creating a string request to send request to the url
        dialog.setMessage("wait");
        dialog.setCancelable(true);
        dialog.show();
        String hp = context.getString(R.string.link) + "api/register";
        hp = hp.replace(" ", "%20");
//        hp= hp + "?mobile_no=" + phoneNumber + "&token=" + context.getSharedPreferences(context.getString(R.string.shared_pref_name), MODE_PRIVATE).getString("token", "");
        Log.e("url", hp + "?mobile_no=" + phoneNumber + "&token=" + context.getSharedPreferences(context.getString(R.string.shared_pref_name), MODE_PRIVATE).getString("token", ""));
        StringRequest stringRequest = new StringRequest(Request.Method.POST, hp,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        //hiding the progressbar after completion
                        Log.e("Response", response);
                        try {
                            JSONObject jo_main = new JSONObject(response);
                            JSONObject jo_data = jo_main.getJSONObject("data");
                            if (jo_data.getString("success").equals("1")) {
                                int userId = jo_data.getJSONObject("register").getInt("user_id");
                                Toast.makeText(context, R.string.reg_succezss, Toast.LENGTH_SHORT).show();
                                context.getSharedPreferences(context.getString(R.string.shared_pref_name), MODE_PRIVATE).edit().putString("registeredNumber", phoneNumber).apply();
                                context.getSharedPreferences(context.getString(R.string.shared_pref_name), MODE_PRIVATE).edit().putInt("userId", userId).apply();
                                Log.e( "", "onResponse:sdgsggsd "+ currentNumber);
                            } else {
                                Toast.makeText(context, jo_data.optString("register"), Toast.LENGTH_SHORT).show();
                            }
                            context.getSharedPreferences(context.getString(R.string.shared_pref_name), MODE_PRIVATE).edit().putBoolean("isFirstTimeRegister", false).apply();
                            dialog.dismiss();
                        } catch (JSONException e) {
                            e.printStackTrace();
                            dialog.dismiss();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //displaying the error in toast if occurs
                        NetworkResponse networkResponse = error.networkResponse;
                        if (networkResponse != null) {
                            Log.e("Status code", String.valueOf(networkResponse.statusCode));
                            Toast.makeText(context, String.valueOf(networkResponse.statusCode), Toast.LENGTH_SHORT).show();
                        }
                        dialog.dismiss();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("mobile_no", phoneNumber);
                params.put("token", context.getSharedPreferences(context.getString(R.string.shared_pref_name), MODE_PRIVATE).getString("token", ""));
                return params;
            }
        };
        //creating a request queue
        RequestQueue requestQueue = Volley.newRequestQueue(context);
        //adding the string request to request queue
        requestQueue.add(stringRequest);
    }

    private boolean isValid(EditText editText) {
        phoneNumber = editText.getText().toString();
        return editText.length() != 0;
    }


}
