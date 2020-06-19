package freaktemplate.kingburger.getSet;


import freaktemplate.kingburger.timeline.OrderStatus;

class timelineGetSet {

    private String mTitle;
    private String mDate;
    private OrderStatus mStatus;

    public String getmTitle() {
        return mTitle;
    }

    public void setmTitle(String mTitle) {
        this.mTitle = mTitle;
    }

    public String getmDate() {
        return mDate;
    }

    public void setmDate(String mDate) {
        this.mDate = mDate;
    }

    public OrderStatus getmStatus() {
        return mStatus;
    }

    public void setmStatus(OrderStatus mStatus) {
        this.mStatus = mStatus;
    }
}
