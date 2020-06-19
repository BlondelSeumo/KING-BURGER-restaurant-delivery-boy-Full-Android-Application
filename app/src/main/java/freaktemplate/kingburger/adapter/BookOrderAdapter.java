package freaktemplate.kingburger.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;
import freaktemplate.kingburger.observableLayer.Order;

import java.util.List;
import java.util.Locale;

public class BookOrderAdapter extends BaseAdapter {
    private final List <Order> data;
    Context context;

    public BookOrderAdapter(List <Order> data, Context context) {
        this.data = data;
        this.context = context;
    }

    @Override
    public int getCount() {
        return data == null ? 0 : data.size();
    }

    @Override
    public Object getItem(int position) {
        return data == null ? 0 : data.get( position );

    }

    @Override
    public long getItemId(int position) {
        return data == null ? -1 : data.get( position ).getId();
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        Context context = parent.getContext();
        View view = convertView;
        if (view == null) {
            view = LayoutInflater.from( parent.getContext() ).inflate( R.layout.cell_book_order, parent, false );
        }
        TextView tv_item_name = view.findViewById( R.id.tv_item_name );
        TextView tv_item_price = view.findViewById( R.id.tv_item_price );
        TextView tv_subItem_list = view.findViewById( R.id.tv_subItem_list );
        tv_item_name.setTypeface( Home.tf_main_bold );
        tv_item_price.setTypeface( Home.tf_main_bold );
        tv_subItem_list.setTypeface( Home.tf_main_regular );
        tv_item_name.setText( context.getString( R.string.orderno ) + " : " + data.get( position ).getOrderId() );
        tv_item_price.setText( String.format( Locale.ENGLISH, "%s %.2f", context.getString( R.string.currency ), data.get( position ).getOrderPrice() ) );


        tv_subItem_list.setText( data.get( position ).getAddress() );
        return view;
    }
}
