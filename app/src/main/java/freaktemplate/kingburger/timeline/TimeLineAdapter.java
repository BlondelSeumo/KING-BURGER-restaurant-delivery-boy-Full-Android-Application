package freaktemplate.kingburger.timeline;

import android.content.Context;
import android.graphics.drawable.Drawable;
import android.graphics.drawable.GradientDrawable;
import android.graphics.drawable.LayerDrawable;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.github.vipulasri.timelineview.TimelineView;
import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;

import java.util.List;

/**
 * Created by RedixbitUser on 3/22/2018.
 */

public class TimeLineAdapter extends RecyclerView.Adapter <TimeLineViewHolder> {

    private final List <TimeLineModel> mFeedList;
    private Context mContext;

    public TimeLineAdapter(List <TimeLineModel> feedList) {
        mFeedList = feedList;
    }

    @Override
    public int getItemViewType(int position) {
        return TimelineView.getTimeLineViewType( position, getItemCount() );
    }

    @NonNull
    @Override
    public TimeLineViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        mContext = parent.getContext();
        View view;
        view = LayoutInflater.from( mContext ).inflate( R.layout.cell_timeline, parent, false );
        return new TimeLineViewHolder( view, viewType );
    }

    @Override
    public void onBindViewHolder(@NonNull TimeLineViewHolder holder, int position) {

        GradientDrawable layer1 = new GradientDrawable();
        layer1.setShape( GradientDrawable.OVAL );
        layer1.setSize( 32, 32 );

        //default settings
        Drawable layer2 = null;
        LayerDrawable layerDrawable;
        TimeLineModel timeLineModel = mFeedList.get( position );
        holder.mMessage.setTypeface( Home.tf_main_bold );
        layer1.setColor( mContext.getResources().getColor( R.color.colorGrey ) );
        holder.mMessage.setTypeface( Home.tf_main_bold );
        holder.mDate.setTypeface( Home.tf_main_regular );
        switch (position) {
            case 0:
                holder.mMessage.setText( R.string.txt_order_placed );
                layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus1 );

                break;
            case 1:
                holder.mMessage.setText( R.string.txt_preparing );
                layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus2 );

                break;
            case 2:
                holder.mMessage.setText( R.string.txt_dispatching );
                layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus3 );

                break;
            case 3:
                holder.mMessage.setText( R.string.txt_delivered );
                layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus4 );
                break;
        }
        layerDrawable = new LayerDrawable( new Drawable[]{layer1, layer2} );
        holder.mTimelineView.setMarker( layerDrawable );


        //setting According to status

        switch (position) {
            case 0:
                if (timeLineModel.getStatus().equals( OrderStatus.ACTIVE )) {
                    layer1.setColor( mContext.getResources().getColor( R.color.colorBlue ) );
                    layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus1 );
                    holder.mTimelineView.setStartLine( mContext.getResources().getColor( R.color.white ), getItemViewType( position ) );
                    holder.mTimelineView.setEndLine( mContext.getResources().getColor( R.color.white ), getItemViewType( position ) );                    //holder.mTimelineView.setStartLine( mContext.getResources().getColor( R.color.colorBlue ), getItemViewType( position ) );


                }
                break;
            case 1:
                if (timeLineModel.getStatus().equals( OrderStatus.ACTIVE )) {
                    layer1.setColor( mContext.getResources().getColor( R.color.colorBlue ) );
                    layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus2 );
                    holder.mTimelineView.setStartLine( mContext.getResources().getColor( R.color.white ), getItemViewType( position ) );
                    holder.mTimelineView.setEndLine( mContext.getResources().getColor( R.color.white ), getItemViewType( position ) );

                }
                break;
            case 2:
                if (timeLineModel.getStatus().equals( OrderStatus.ACTIVE )) {
                    layer1.setColor( mContext.getResources().getColor( R.color.colorBlue ) );
                    layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus3 );
                    holder.mTimelineView.setStartLine( mContext.getResources().getColor( R.color.white ), getItemViewType( position ) );
                    holder.mTimelineView.setEndLine( mContext.getResources().getColor( R.color.white ), getItemViewType( position ) );

                }
                break;
            case 3:
                if (timeLineModel.getStatus().equals( OrderStatus.ACTIVE )) {
                    layer1.setColor( mContext.getResources().getColor( R.color.colorBlue ) );
                    layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus4 );
                    holder.mTimelineView.setStartLine( mContext.getResources().getColor( R.color.white ), getItemViewType( position ) );
                    holder.mTimelineView.setEndLine( mContext.getResources().getColor( R.color.white ), getItemViewType( position ) );

                } else if (timeLineModel.getStatus().equals( OrderStatus.CANCELLED )) {
                    holder.mMessage.setText( R.string.cancelled );
                    layer1.setColor( mContext.getResources().getColor( R.color.colorBlue ) );
                    layer2 = mContext.getResources().getDrawable( R.drawable.orderstatus4 );

                }
                break;

        }
        if (timeLineModel.getStatus().equals( OrderStatus.ACTIVE )) {
            layerDrawable = new LayerDrawable( new Drawable[]{layer1, layer2} );
            holder.mTimelineView.setMarker( layerDrawable );
        }
        Log.e( "date", timeLineModel.getDate() );
        holder.mDate.setText( timeLineModel.getDate() );
    }

    @Override
    public int getItemCount() {
        return 4;
    }
}
