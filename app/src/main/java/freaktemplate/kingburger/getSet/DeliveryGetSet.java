package freaktemplate.kingburger.getSet;

public class DeliveryGetSet {
    private String isComplete;
    private String orderNo;
    private String orderAmount;
    private String orderQuantity;
    private String orderTimeDate;

    public String getComplete() {
        return isComplete;
    }

    public void setComplete(String complete) {
        isComplete = complete;
    }

    public String getOrderNo() {
        return orderNo;
    }

    public void setOrderNo(String orderNo) {
        this.orderNo = orderNo;
    }

    public String getOrderAmount() {
        return orderAmount;
    }

    public void setOrderAmount(String orderAmount) {
        this.orderAmount = orderAmount;
    }

    public String getOrderQuantity() {
        return orderQuantity;
    }

    public void setOrderQuantity(String orderQuantity) {
        this.orderQuantity = orderQuantity;
    }

    public String getOrderTimeDate() {
        return orderTimeDate;
    }

    public void setOrderTimeDate(String orderTimeDate) {
        this.orderTimeDate = orderTimeDate;
    }
}
