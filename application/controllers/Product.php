<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('product_model');
	}

	function index(){
		$data['data']=json_decode(file_get_contents('https://fakestoreapi.com/products'));
		
		$this->load->view('product_view',$data);
	}
    function details($id){
		$data['data']=json_decode(file_get_contents("https://fakestoreapi.com/products/$id"));
		
		$this->load->view('product_details',$data);
	}
	
	function updateItemQty(){
        $update = 0;
        
        // Get cart item info
        $rowid = $this->input->post('rowid');
        $qty = $this->input->post('qty');
        
        // Update item in the cart
        if(!empty($rowid) && !empty($qty)){
            $data = array(
                'rowid' => $rowid,
                'qty'   => $qty
            );
            $update = $this->cart->update($data);
        }
        
        // Return response
        echo $update?'ok':'err';
    }
    
    function checkout(){
        $this->load->library('form_validation');
        $this->load->helper('form');
        // Redirect if the cart is empty
        if($this->cart->total_items() <= 0){
            redirect('/');
        }
        
        $custData = $data = array();
        $account = $this->input->post('account');
        // If order request is submitted
        $submit = $this->input->post('placeOrder');
        if(isset($submit)){
            // Form field validation rules
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if($account == "register"){
            $this->form_validation->set_rules('password', 'Password', 'required');
                
            }
            $this->form_validation->set_rules('phone', 'Phone', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            
            // Prepare customer data
            $custData = array(
                'name'     => strip_tags($this->input->post('name')),
                'email'     => strip_tags($this->input->post('email')),
                'phone'     => strip_tags($this->input->post('phone')),
                'address'=> strip_tags($this->input->post('address'))
            );
            if($account == "register"){
                    $custData['password'] = password_hash($password,PASSWORD_DEFAULT);
            }
            
            // Validate submitted form data
            if($this->form_validation->run() == true){
                
                // Insert customer data
                $insert = $this->insertCustomer($custData);
                
                // Check customer data insert status
                if($insert){
                    // Insert order
                    $order = $this->placeOrder($insert);
                    
                    // If the order submission is successful
                    if($order){
                        $this->session->set_userdata('success_msg', 'Order placed successfully.');
                        redirect(site_url('product/orderSuccess/').$order);
                    }else{
                        $data['error_msg'] = 'Order submission failed, please try again.';
                    }
                }else{
                    $data['error_msg'] = 'Some problems occured, please try again.';
                }
            }
            
        }
        
        // Customer data
        $data['custData'] = $custData;
        
        // Pass products data to the view
        $this->load->view('checkout', $data);
    }
    
    public function insertCustomer($data){
        // Add created and modified date if not included
        if(!array_key_exists("created", $data)){
            $data['created'] = date("Y-m-d H:i:s");
        }
        if(!array_key_exists("modified", $data)){
            $data['modified'] = date("Y-m-d H:i:s");
        }
        
        // Insert customer data
        $insert = $this->db->insert('customers', $data);

        // Return the status
        return $insert?$this->db->insert_id():false;
    }
    
    function placeOrder($custID){
        // Insert order data
        $ordData = array(
            'customer_id' => $custID,
            'grand_total' => $this->cart->total()
        );
        $insertOrder = $this->insertOrder($ordData);
        
        if($insertOrder){
            // Retrieve cart data from the session
            $cartItems = $this->cart->contents();
            
            // Cart items
            $ordItemData = array();
            $i=0;
            foreach($cartItems as $item){
                $ordItemData[$i]['order_id']     = $insertOrder;
                $ordItemData[$i]['product_id']     = $item['id'];
                $ordItemData[$i]['quantity']     = $item['qty'];
                $ordItemData[$i]['sub_total']     = $item["subtotal"];
                $i++;
            }
            
            if(!empty($ordItemData)){
                // Insert order items
                $insertOrderItems = $this->insertOrderItems($ordItemData);
                
                if($insertOrderItems){
                    // Remove items from the cart
                    $this->cart->destroy();
                    
                    // Return order ID
                    return $insertOrder;
                }
            }
        }
        return false;
    }
    
    public function insertOrder($data){
        // Add created and modified date if not included
        if(!array_key_exists("created", $data)){
            $data['created'] = date("Y-m-d H:i:s");
        }
        if(!array_key_exists("modified", $data)){
            $data['modified'] = date("Y-m-d H:i:s");
        }
        
        // Insert order data
        $insert = $this->db->insert('orders', $data);

        // Return the status
        return $insert?$this->db->insert_id():false;
    }
    
    /*
     * Insert order items data in the database
     * @param data array
     */
    public function insertOrderItems($data = array()) {
        
        // Insert order items
        $insert = $this->db->insert_batch('order_items', $data);

        // Return the status
        return $insert?true:false;
    }
    
    function orderSuccess($ordID){
        // Fetch order data from the database
        $data['order'] = $this->getOrder($ordID);
        
        // Load order details view
        $this->load->view('order-success', $data);
    }
    
    public function getOrder($id){
        $this->db->select('o.*, c.name, c.email, c.phone, c.address');
        $this->db->from('orders as o');
        $this->db->join('customers as c', 'c.id = o.customer_id', 'left');
        $this->db->where('o.id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
       
        // Return fetched data
        return !empty($result)?$result:false;
    }
    
	function add_to_cart(){ 
		$data = array(
			'id' => $this->input->post('product_id'), 
			'name' => $this->input->post('product_name'), 
			'price' => $this->input->post('product_price'), 
			'qty' => $this->input->post('quantity'), 
		);
		$this->cart->insert($data);
		echo $this->show_cart(); 
	}

	function show_main_cart(){ 
		$output = '';
		$no = 0;
		foreach ($this->cart->contents() as $items) {
			$no++;
			$output .='
				<tr>
					<td>'.$items['name'].'</td>
					<td>'.number_format($items['price']).'</td>
					<td><input type="number" class="itmqty form-control" data-id="'.$items['rowid'].'" value="'.$items['qty'].'"  /></td>
					<td>'.number_format($items['subtotal']).'</td>
					<td><button type="button" id="'.$items['rowid'].'" class="romove_cart btn btn-danger btn-sm">Cancel</button></td>
				</tr>
			';
		}
		$output .= '
			<tr>
				<th colspan="3">Total</th>
				<th colspan="2">'.$this->config->item('currency').''.number_format($this->cart->total()).'</th>
			</tr>
		';
		return $output;
	}
	
	function show_cart(){ 
		$output = '';
		$no = 0;
		$output .='<span class="show-cart-btn">
    	 <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-cart" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg><span class="total-shopping-cart cart-total-full">
    <span class="items_cart">'.count($this->cart->contents()).'</span> </span>
    	<div class="minicart"><div class="minicart--item-container">You have <span class="minicart--item-count" style="font-weight: 600">'.count($this->cart->contents()).' items</span> in your cart!</div><hr>';
        $output .='<ul>';
		foreach ($this->cart->contents() as $items) {
			$no++;
			$output .='
				<li class="minicart--item">
				<h1 class="title">'.$items['name'].'</h1>
					<p class="price">'.$this->config->item('currency').''.number_format($items['price']).' X '.$items['qty'].'</p>
					<p><b>Subtotal :  </b>'.$this->config->item('currency').''.number_format($items['subtotal']).'</p>
					<p class="remove"><button type="button" id="'.$items['rowid'].'" class="romove_cart btn btn-danger btn-sm">Cancel</button></p>
				</li>
			';
		}
		$output .= '</ul><div class="minicart--subtotal"><p class="minicart--subtotal-title"><b>Total Amount : '.$this->config->item('currency').''.number_format($this->cart->total()).'</b></p></div>';
		if(!empty($this->cart->contents())){
		   $output .= '<a href="'.site_url('product/checkout').'" class="checkoutbtn btn">Checkout</a>'; 
		}
		$output .= '</div></span>';
		return $output;
	}

	function load_cart(){ 
		echo $this->show_cart();
	}
    function load_cart_detail(){ 
		echo $this->show_main_cart();
	}
	function delete_cart(){ 
		$data = array(
			'rowid' => $this->input->post('row_id'), 
			'qty' => 0, 
		);
		$this->cart->update($data);
		echo $this->show_cart();
	}
}