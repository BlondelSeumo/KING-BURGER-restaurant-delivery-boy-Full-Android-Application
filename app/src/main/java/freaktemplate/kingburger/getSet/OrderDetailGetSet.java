package freaktemplate.kingburger.getSet;

public class OrderDetailGetSet {
    private String orderDate;
    private String numberOfItems;
    private String orderDeliveryAddress;
    private String orderDeliveryPhone;
    private String orderTotalAmount;

    public String getOrderDate() {
        return orderDate;
    }

    public void setOrderDate(String orderDate) {
        this.orderDate = orderDate;
    }

    public String getNumberOfItems() {
        return numberOfItems;
    }

    public void setNumberOfItems(String numberOfItems) {
        this.numberOfItems = numberOfItems;
    }

    public String getOrderDeliveryAddress() {
        return orderDeliveryAddress;
    }

    public void setOrderDeliveryAddress(String orderDeliveryAddress) {
        this.orderDeliveryAddress = orderDeliveryAddress;
    }

    public String getOrderDeliveryPhone() {
        return orderDeliveryPhone;
    }

    public void setOrderDeliveryPhone(String orderDeliveryPhone) {
        this.orderDeliveryPhone = orderDeliveryPhone;
    }

    public String getOrderTotalAmount() {
        return orderTotalAmount;
    }

    public void setOrderTotalAmount(String orderTotalAmount) {
        this.orderTotalAmount = orderTotalAmount;
    }
}
