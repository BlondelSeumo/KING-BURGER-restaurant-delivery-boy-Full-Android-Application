
package freaktemplate.kingburger.getSet;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Forgotpojjo {

    @SerializedName("success")
    @Expose
    private Integer success;
    @SerializedName("data")
    @Expose
    private String data;

    /**
     * No args constructor for use in serialization
     * 
     */
    public Forgotpojjo() {
    }

    /**
     * 
     * @param data
     * @param success
     */
    public Forgotpojjo(Integer success, String data) {
        super();
        this.success = success;
        this.data = data;
    }

    public Integer getSuccess() {
        return success;
    }

    public void setSuccess(Integer success) {
        this.success = success;
    }

    public String getData() {
        return data;
    }

    public void setData(String data) {
        this.data = data;
    }

}
