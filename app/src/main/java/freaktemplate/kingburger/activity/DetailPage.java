package freaktemplate.kingburger.activity;

import androidx.lifecycle.ViewModelProviders;

import android.app.Dialog;
import android.content.Intent;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.text.SpannableString;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.View;
import android.view.WindowManager;
import android.view.animation.RotateAnimation;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NetworkResponse;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.bumptech.glide.Glide;
import com.diegodobelo.expandingview.ExpandingItem;
import com.diegodobelo.expandingview.ExpandingList;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.adapter.IngredientCustomAdapter;
import freaktemplate.kingburger.getSet.SubMenuGetSet;
import freaktemplate.kingburger.observableLayer.Cart;
import freaktemplate.kingburger.observableLayer.CartFav;
import freaktemplate.kingburger.observableLayer.CartItemTopping;
import freaktemplate.kingburger.observableLayer.CartListViewModel;
import freaktemplate.kingburger.utils.InternetDialog;
import freaktemplate.kingburger.utils.LanguageSelectore;
import freaktemplate.kingburger.utils.SPmanager;
import freaktemplate.kingburger.utils.TopAlignSuperscriptSpan;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class DetailPage extends AppCompatActivity {
    private ImageView img_slider;
    private ImageView iv_fav;
    private TextView tv_itemPrice;
    private int counter = 0;
    private ListView ll_free_item;
    private ListView ll_paid_item;
    private IngredientCustomAdapter ingredientCustomAdapterFree;
    private IngredientCustomAdapter ingredientCustomAdapterPaid;
    private List<CartItemTopping> data_free;
    private List<CartItemTopping> data_paid;
    private CartListViewModel cartListViewModel;
    private SubMenuGetSet subMenuGetSet;
    private Boolean isFav = false;
    private static double currentPrice = 0;
    private RelativeLayout rel_quantity;
    private TextView tv_select_ingredients;
    private int itemQuantity = 1;
    private RelativeLayout rl_AddToCart;
    private String order_status;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LanguageSelectore.setLanguageIfAR(DetailPage.this);
        setContentView(R.layout.activity_detail_page);


        //assign view model of database for the activity
        cartListViewModel = ViewModelProviders.of(this).get(CartListViewModel.class);


        //get intents from the previous activity to be used in the activity
        gettingIntents();

        //initialize views
        initView();


        //request data for ingredient from server
        if (Home.isNetworkConnected(DetailPage.this))
            getDataFromServer();
        else new InternetDialog(DetailPage.this, new InternetDialog.onRetry() {
            @Override
            public void onReload() {
                getDataFromServer();
            }
        }).show();

    }

    private void getDataFromServer() {
        getIngredientList(Integer.parseInt(subMenuGetSet.getItemId()));
    }

    private void getIngredientList(int menuId) {
        final List<CartItemTopping> tempData = new ArrayList<>();
        //creating a string request to send request to the url
        String hp = getApplication().getString(R.string.link) + "api/ingredients?menu_id=" + menuId;
        Log.e("tag", "getIngredientList: " + hp);
        hp = hp.replace(" ", "%20");
        Log.w(getClass().getName(), hp);
        StringRequest stringRequest = new StringRequest(Request.Method.GET, hp,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        //hiding the progressbar after completion
                        Log.e("Responsesjmflkdsf'gf", response);

                        try {
                            JSONObject jo_object = new JSONObject(response);
                            JSONArray ja_ingredient = jo_object.optJSONArray("ingredients");

                            if (ja_ingredient.length() > 0) {

                                JSONObject jo_free_data = ja_ingredient.optJSONObject(0);
                                JSONObject jo_paid_data = ja_ingredient.optJSONObject(1);

                                JSONArray ja_free_data_detail = jo_free_data.optJSONArray("free");
                                //parse free ingredient
                                CartItemTopping cartItemTopping;
                                for (int i = 0; i < ja_free_data_detail.length(); i++) {
                                    JSONObject freeData = ja_free_data_detail.optJSONObject(i);
                                    cartItemTopping = new CartItemTopping();
                                    cartItemTopping.setToppingId(freeData.optInt("id"));
                                    cartItemTopping.setToppingName(freeData.optString("item_name"));
                                    cartItemTopping.setType(freeData.optInt("type"));
                                    cartItemTopping.setToppingPrice(freeData.optDouble("price"));
                                    cartItemTopping.setType(freeData.optInt("type"));
                                    cartItemTopping.setItemId(Integer.parseInt(subMenuGetSet.getItemId()));
                                    tempData.add(cartItemTopping);
                                }

                                JSONArray ja_paid_data_detail = jo_free_data.optJSONArray("paid");

                                //parse paid ingredient
                                for (int i = 0; i < ja_paid_data_detail.length(); i++) {
                                    JSONObject paidData = ja_paid_data_detail.optJSONObject(i);
                                    cartItemTopping = new CartItemTopping();
                                    cartItemTopping.setToppingId(paidData.optInt("id"));
                                    cartItemTopping.setToppingName(paidData.optString("item_name"));
                                    cartItemTopping.setType(paidData.optInt("type"));
                                    cartItemTopping.setToppingPrice(paidData.optDouble("price"));
                                    cartItemTopping.setType(paidData.optInt("type"));
                                    cartItemTopping.setChecked(false);
                                    cartItemTopping.setItemId(Integer.parseInt(subMenuGetSet.getItemId()));
                                    tempData.add(cartItemTopping);
                                }


                                //update the UI according to data
                                updateUiIngredient(tempData);

                            }

                        } catch (Exception e) {
                            e.printStackTrace();
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
                            Toast.makeText(getApplication(), String.valueOf(networkResponse.statusCode), Toast.LENGTH_SHORT).show();
                        }
                    }
                });
        stringRequest.setShouldCache(false);

        stringRequest.setRetryPolicy(new DefaultRetryPolicy(20000, 1, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        //creating a request queue
        RequestQueue requestQueue = Volley.newRequestQueue(getApplication());
        //adding the string request to request queue

        requestQueue.add(stringRequest);

    }

    private void updateUiIngredient(List<CartItemTopping> data) {
        data_paid = new ArrayList<>();
        data_free = new ArrayList<>();


        //divide the data according to free or paid to populate them in different listView
        for (CartItemTopping item : data) {
            if (item.getType() == 0) {
                data_free.add(item);
            } else {
                data_paid.add(item);
            }
        }

        if (data_paid != null) {
            ingredientCustomAdapterFree = new IngredientCustomAdapter(DetailPage.this, data_paid);
            ll_paid_item.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                    CheckBox cb = view.findViewById(R.id.cb);
                    if (!cb.isChecked()) {
                        //increase rate of item according to topping if selected
                        currentPrice = currentPrice + data_paid.get(position).getToppingPrice();
                        double updatePrice = currentPrice * itemQuantity;
                        updateItemPrice(updatePrice);
                        data_paid.get(position).setChecked(true);
                        cb.setChecked(true);

                    } else {
                        currentPrice = currentPrice - data_paid.get(position).getToppingPrice();
                        double updatePrice = currentPrice * itemQuantity;
                        updateItemPrice(updatePrice);
                        data_paid.get(position).setChecked(false);
                        cb.setChecked(false);
                    }

                }
            });
            ll_paid_item.setAdapter(ingredientCustomAdapterFree);
        }

        if (data_free != null) {
            ingredientCustomAdapterPaid = new IngredientCustomAdapter(DetailPage.this, data_free);
            ll_free_item.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                    CheckBox cb = view.findViewById(R.id.cb);
                    if (!cb.isChecked()) {
                        data_free.get(position).setChecked(true);
                        cb.setChecked(true);

                    } else {
                        data_free.get(position).setChecked(false);
                        cb.setChecked(false);
                    }
                }
            });
            ll_free_item.setAdapter(ingredientCustomAdapterPaid);
        }
    }

    private void gettingIntents() {
        //getting Intents
        Intent intent = getIntent();
        ArrayList<String> itemDetail = intent.getStringArrayListExtra("itemDetail");
        subMenuGetSet = new SubMenuGetSet();
        subMenuGetSet.setCatId(itemDetail.get(0));
        subMenuGetSet.setItemId(itemDetail.get(1));
        subMenuGetSet.setItemName(itemDetail.get(2));
        subMenuGetSet.setItemDescription(itemDetail.get(3));
        subMenuGetSet.setItemPrice(itemDetail.get(4));
        subMenuGetSet.setItemImage(itemDetail.get(5));
        Log.e("TAG", "DetailPage: " + itemDetail.get(1) + "submenuGetSet" + subMenuGetSet.getItemId());
    }

    private void initView() {

        ImageView imgGif = findViewById(R.id.imgGif);
        int screenHeight = calculateScreenHeight();

        tv_itemPrice = findViewById(R.id.tv_itemPrice);
        TextView tv_item_detail = findViewById(R.id.tv_item_detail);
        ImageView iv_itemImage = findViewById(R.id.iv_itemImage);
        ImageView img_back = findViewById(R.id.img_back);
        iv_fav = findViewById(R.id.iv_fav);
        rl_AddToCart = findViewById(R.id.rl_AddToCart);

        isFav = cartListViewModel.getFavItem(Integer.parseInt(subMenuGetSet.getItemId()), Integer.parseInt(subMenuGetSet.getCatId()));

        if (isFav) {
            Log.e("Check", "isFav");
            iv_fav.setImageResource(R.drawable.btn_fav_yes);

        } else iv_fav.setImageResource(R.drawable.ic_btn_fav);


        TextView tv_itemName = findViewById(R.id.tv_itemName);
        Glide.with(DetailPage.this).load(R.drawable.ezgif).into(imgGif);


        currentPrice = Double.parseDouble(subMenuGetSet.getItemPrice());
        updateItemPrice(currentPrice);


        tv_itemPrice.setTypeface(Home.tf_main_bold);
        tv_itemName.setTypeface(Home.tf_main_bold);
        tv_item_detail.setTypeface(Home.tf_main_italic);

        tv_itemName.setText(subMenuGetSet.getItemName());
        tv_item_detail.setText(subMenuGetSet.getItemDescription());
        String imageUrl = getString(R.string.link) + "public/upload/images/menu_item_icon/" + subMenuGetSet.getItemImage();
        Glide.with(DetailPage.this).load(imageUrl).into(iv_itemImage);


        //setting expandable list;
        ExpandingList expandingList = findViewById(R.id.expanding_list_main);
        ExpandingItem item = expandingList.createNewItem(R.layout.expanding_layout);
        img_slider = item.findViewById(R.id.img_slider);
        TextView tv_ingredientTittle = item.findViewById(R.id.tv_ingredientTittle);
        tv_ingredientTittle.setTypeface(Home.tf_main_bold);


        tv_select_ingredients = item.findViewById(R.id.tv_select_ingredients);

        rel_quantity = item.findViewById(R.id.rel_quantity);
        TextView tv_select_quantity = item.findViewById(R.id.tv_select_quantity);
        final TextView tv_quantity = item.findViewById(R.id.tv_quantity);
        tv_quantity.setText(String.valueOf(itemQuantity));
        tv_quantity.setTypeface(Home.tf_main_bold);
        ImageButton ib_plus = item.findViewById(R.id.ib_plus);
        ImageButton ib_minus = item.findViewById(R.id.ib_minus);

        ib_plus.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int newQuantity = Integer.valueOf(tv_quantity.getText().toString()) + 1;
                itemQuantity = newQuantity;
                tv_quantity.setText(String.valueOf(newQuantity));
                double newCurrentPrice = currentPrice * itemQuantity;
                updateItemPrice(newCurrentPrice);
            }
        });

        ib_minus.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (itemQuantity > 1) {
                    int newQuantity = Integer.valueOf(tv_quantity.getText().toString()) - 1;
                    itemQuantity = newQuantity;
                    tv_quantity.setText(String.valueOf(newQuantity));
                    double newCurrentPrice = currentPrice * itemQuantity;
                    updateItemPrice(newCurrentPrice);
                } else
                    Toast.makeText(DetailPage.this, R.string.item_warining, Toast.LENGTH_SHORT).show();
            }
        });

        img_back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                onBackPressed();
            }
        });
        tv_select_ingredients.setTypeface(Home.tf_main_regular);
        tv_select_quantity.setTypeface(Home.tf_main_regular);
        String textSelectIngredient = getString(R.string.textSelectIngredient);
        tv_select_ingredients.setText(String.format("%s %s", textSelectIngredient, subMenuGetSet.getItemName()));

        int subItemHeight = (int) (screenHeight / 2.7);
        item.createSubItems(1);
        View view_subItem = item.getSubItemView(0);

        RelativeLayout rl_top_bottom_menu = view_subItem.findViewById(R.id.rl_top_bottom_menu);
        rl_top_bottom_menu.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, subItemHeight));

        ll_free_item = view_subItem.findViewById(R.id.ll_free_item);
        ll_paid_item = view_subItem.findViewById(R.id.ll_paid_item);
        TextView tv_free_ingredient_tittle = view_subItem.findViewById(R.id.tv_free_ingredient_tittle);
        TextView tv_paid_ingredient_tittle = view_subItem.findViewById(R.id.tv_paid_ingredient_tittle);
        tv_free_ingredient_tittle.setTypeface(Home.tf_main_bold);
        tv_paid_ingredient_tittle.setTypeface(Home.tf_main_bold);


        item.setStateChangedListener(new ExpandingItem.OnItemStateChanged() {
            @Override
            public void itemCollapseStateChanged(boolean expanded) {
                if (expanded) {
                    rel_quantity.setVisibility(View.VISIBLE);
                    tv_select_ingredients.setVisibility(View.GONE);
                    rotate(counter);
                    counter++;
                } else {
                    rel_quantity.setVisibility(View.GONE);
                    tv_select_ingredients.setVisibility(View.VISIBLE);
                    rotate(counter);
                    counter++;
                }
            }
        });

        order_status = SPmanager.getPreference(DetailPage.this, "order_status");
        rl_AddToCart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                /*if (order_status.equals("1")) {
                    addToCart(v);
                } else {
                    showdailog();
                }*/
                addToCart(v);

            }
        });
    }

    private void showdailog() {
        Dialog d = new Dialog(DetailPage.this);
        d.setContentView(R.layout.status_dailog);
        d.getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        d.show();
        d.getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);

        Button buttonCancel;
        buttonCancel = d.findViewById(R.id.buttonCancel);

        buttonCancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(DetailPage.this, Home.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
            }
        });
    }

    private void updateItemPrice(Double itemInitialPrice) {
        try {
            String itemPrice = String.format(Locale.ENGLISH, "%s %.2f", getString(R.string.currency), itemInitialPrice);
            tv_itemPrice.setText(createStringDiffSize(itemPrice));
        } catch (ArrayIndexOutOfBoundsException e) {
            e.printStackTrace();
        }
    }

    @Override
    public void onBackPressed() {
        Intent intent = new Intent(DetailPage.this, Home.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        super.onBackPressed();
    }

    private void rotate(int counter) {
        final RotateAnimation rotateAnim = new RotateAnimation(180 * counter, 180 * (counter + 1),
                RotateAnimation.RELATIVE_TO_SELF, 0.5f,
                RotateAnimation.RELATIVE_TO_SELF, 0.5f);
        rotateAnim.setDuration(350);
        rotateAnim.setFillEnabled(true);
        rotateAnim.setFillAfter(true);
        img_slider.startAnimation(rotateAnim);
    }

    private int calculateScreenHeight() {
        DisplayMetrics metrics = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(metrics);
        return metrics.heightPixels;
    }

    private SpannableString createStringDiffSize(String s) {
        if (!s.contains(".")) {
            return new SpannableString(s);
        }
        String[] splitPrice = s.split("\\.");
        StringBuilder tempItemPrice = new StringBuilder();
        tempItemPrice.append(splitPrice[0]).append(".").append(splitPrice[1]);
        SpannableString spanItemPrice = new SpannableString(tempItemPrice.toString());
        spanItemPrice.setSpan(new TopAlignSuperscriptSpan((float) 0.3), splitPrice[0].length() + 1, tempItemPrice.toString().length(), 0); // set size
        return spanItemPrice;
    }

    public void onBackPress(View view) {
        Intent intent = new Intent(DetailPage.this, Home.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);

    }

    public void addToFav(View view) {
        isFav = cartListViewModel.getFavItem(Integer.parseInt(subMenuGetSet.getItemId()), Integer.parseInt(subMenuGetSet.getCatId()));
        if (isFav) {
            CartFav cart = new CartFav();
            cart.setItemId(Integer.parseInt(subMenuGetSet.getItemId()));
            cart.setItemName(subMenuGetSet.getItemName());
            cart.setItemDescription(subMenuGetSet.getItemDescription());
            cart.setIsFav(0);
            cart.setItemPrice(Double.parseDouble(subMenuGetSet.getItemPrice()));
            cart.setItemCategoryId(Integer.parseInt(subMenuGetSet.getCatId()));
            cart.setItemImageId(subMenuGetSet.getItemImage());
            cartListViewModel.insertFavItem(cart);
            iv_fav.setImageResource(R.drawable.ic_btn_fav);
        } else {
            CartFav cart = new CartFav();
            cart.setItemId(Integer.parseInt(subMenuGetSet.getItemId()));
            cart.setItemName(subMenuGetSet.getItemName());
            cart.setItemDescription(subMenuGetSet.getItemDescription());
            cart.setIsFav(1);
            cart.setItemPrice(Double.parseDouble(subMenuGetSet.getItemPrice()));
            cart.setItemCategoryId(Integer.parseInt(subMenuGetSet.getCatId()));
            cart.setItemImageId(subMenuGetSet.getItemImage());
            cartListViewModel.insertFavItem(cart);
            iv_fav.setImageResource(R.drawable.btn_fav_yes);
        }
    }

    //add item to cart Database
    public void addToCart(View view) {
        Cart cart = new Cart();
        cart.setItemCategoryId(Integer.parseInt(subMenuGetSet.getCatId()));
        cart.setItemPrice(Double.parseDouble(subMenuGetSet.getItemPrice()));
        cart.setItemPriceWithTopping(currentPrice);
        cart.setItemDescription(subMenuGetSet.getItemDescription());
        cart.setItemName(subMenuGetSet.getItemName());
        cart.setItemId(Integer.parseInt(subMenuGetSet.getItemId()));
        cart.setItemQuantity(itemQuantity);


        //insert item details into cart database
        cartListViewModel.insertItem(cart, new CartListViewModel.OnTaskCompleted() {
            @Override
            public void onTaskCompleted() {
                List<CartItemTopping> dataSelectedTopping = new ArrayList<>();

                if (ingredientCustomAdapterFree != null) {
                    if (ingredientCustomAdapterFree.getSelectedData().size() > 0)
                        dataSelectedTopping.addAll(ingredientCustomAdapterFree.getSelectedData());
                }
                if (ingredientCustomAdapterPaid != null) {
                    if (ingredientCustomAdapterPaid.getSelectedData().size() > 0)
                        dataSelectedTopping.addAll(ingredientCustomAdapterPaid.getSelectedData());
                }
                int rowId = cartListViewModel.getRowId();
                for (CartItemTopping topping : dataSelectedTopping) {
                    topping.setOrderId(rowId);
                    topping.setUserId(rowId);
                }

                //    insert topping details into cart topping database
                if (dataSelectedTopping.size() > 0)
                    cartListViewModel.insertItemTopping(dataSelectedTopping);
                Intent i = new Intent(DetailPage.this, Home.class);
                startActivity(i);
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        Toast.makeText(DetailPage.this, R.string.item_added_cart, Toast.LENGTH_SHORT).show();
                    }
                });
            }
        });
    }


}
