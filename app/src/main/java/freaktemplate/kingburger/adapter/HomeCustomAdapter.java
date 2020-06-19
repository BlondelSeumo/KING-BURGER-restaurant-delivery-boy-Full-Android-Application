package freaktemplate.kingburger.adapter;

import android.app.Activity;
import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;
import freaktemplate.kingburger.getSet.SubMenuGetSet;
import freaktemplate.kingburger.interfaces.CustombuttonListener;

import java.util.List;

public class HomeCustomAdapter extends BaseAdapter {

    private final Activity activity;
    private final List<SubMenuGetSet> data;
    private final LayoutInflater inflater;

    public HomeCustomAdapter(Activity a, List<SubMenuGetSet> d) {
        activity = a;
        data = d;
        inflater = (LayoutInflater) activity.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    @Override
    public int getCount() {
        return data.size();
    }

    @Override
    public Object getItem(int position) {
        return position;
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View vi = convertView;
        ImageView iv_item;
        TextView tv_itemName, tv_itemDescription, tv_itemPrice;

        if (convertView == null) {
            vi = inflater.inflate(R.layout.custom_cate_home, parent, false);
        }
        iv_item = vi.findViewById(R.id.iv_item);
        String imageUrl = activity.getString(R.string.link) + "public/upload/images/menu_item_icon/" + data.get(position).getItemImage();
        try {
            Log.e("check", imageUrl);
            Glide.with(activity).load(imageUrl).into(iv_item);
        } catch (NullPointerException e) {
            e.printStackTrace();
        }
        tv_itemName = vi.findViewById(R.id.tv_itemName);
        tv_itemName.setTypeface(Home.tf_main_regular);
        tv_itemDescription = vi.findViewById(R.id.tv_itemDescription);
        tv_itemDescription.setTypeface(Home.tf_main_italic);
        tv_itemPrice = vi.findViewById(R.id.tv_itemPrice);
        tv_itemPrice.setTypeface(Home.tf_main_italic);
        tv_itemName.setText(data.get(position).getItemName());
        tv_itemDescription.setText(data.get(position).getItemDescription());
        tv_itemPrice.setText(String.format("%s %s",activity.getString(R.string.currency), data.get(position).getItemPrice()));
        return vi;
    }
}