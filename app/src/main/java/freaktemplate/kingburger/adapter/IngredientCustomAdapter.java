package freaktemplate.kingburger.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.TextView;

import freaktemplate.kingburger.activity.Home;
import freaktemplate.kingburger.R;
import freaktemplate.kingburger.observableLayer.CartItemTopping;

import java.util.ArrayList;
import java.util.List;

public class IngredientCustomAdapter extends BaseAdapter {
    private final List<CartItemTopping> data;
    private final LayoutInflater layoutInflater;

    public IngredientCustomAdapter(Context context, List<CartItemTopping> data) {
        Context context1 = context;
        this.data = data;
        layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    @Override
    public int getCount() {
        return data.size();
    }

    @Override
    public Object getItem(int position) {
        return data.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }


    public List<CartItemTopping> getSelectedData() {
        List<CartItemTopping> selectedItem = new ArrayList<>();
        for (int i = 0; i < data.size(); i++) {
            if (data.get(i).isChecked())
                selectedItem.add(data.get(i));
        }
        return selectedItem;
    }


    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View customView = convertView;
        if (convertView == null) {
            customView = layoutInflater.inflate(R.layout.cell_ingredient, parent, false);
        }
        TextView tv_ingredientName = customView.findViewById(R.id.tv_ingredientName);
        CheckBox cb = customView.findViewById(R.id.cb);
        tv_ingredientName.setTypeface(Home.tf_main_bold);
        tv_ingredientName.setText(data.get(position).getToppingName());
        cb.setTag(position);

        return customView;
    }


}
