<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Expense extends CI_Controller
{
    private $USER_LOGGED_IN = FALSE;
    private $USER_ID;
    public $encryption;
    public $form_validation ;
    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['USER_ACTIVITY']) && !empty($_COOKIE['USER_ACTIVITY'])) {
            $session_data_raw = $this->encryption->decrypt($_COOKIE['USER_ACTIVITY']);
            $session_data = json_decode($session_data_raw, true);
            if (isset($session_data['user_id']) && !empty($session_data['user_id'])) {
                $this->USER_LOGGED_IN = TRUE;
                $this->USER_ID = $session_data['user_id'];
            }
        }
    }

    public function add_expense()
    {
        if ($this->USER_LOGGED_IN) {
            if ($this->input->method() === 'post') {
                $this->load->library('form_validation');
                // Set rules
                $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('description', 'Description', 'required|min_length[2]|max_length[255]');
                $this->form_validation->set_rules('category', 'Category', 'required');
                if ($this->form_validation->run() == FALSE) {
                    // Return validation errors (for AJAX)
                    echo json_encode([
                        'status' => false,
                        'status_code' => 422,
                        'message' => validation_errors()
                    ]);
                    exit();
                }

                // If validation passed, proceed to insert
                $amount = $this->input->post('amount');
                $description = $this->input->post('description');
                $category = $this->input->post('category');
                $inserted_data = [
                    'user_id' => $this->USER_ID,
                    'amount' => $amount,
                    'description' => $description,
                    'category' => $category
                ];
                $success = $this->db->insert('expense', $inserted_data);
                if ($success) {
                    echo json_encode(['status' => true, 'status_code' => 200, 'message' => '✅ Expense added successfully']);
                } else {
                    echo json_encode(['status' => false, 'status_code' => 500, 'message' => '❌ Failed to add expense']);
                }
                exit();
            }
            $this->load->view('pages/expense/add_expense');
        } else redirect('login');
    }
    public function show_expense()
    {
        if ($this->USER_LOGGED_IN) {
            $user_id = $this->USER_ID;
            $post_params = get_all_input_data();

            // Fetch Expenses
            $this->db->select('*')->from('expense')->where('user_id', $user_id);
            if (!empty($post_params['category'])) {
                $this->db->where('category', $post_params['category']);
            }
            $expense = $this->db->get()->result_array();

            $expenseTable = '';
            $total_amount = 0;
            if (!empty($expense)) {
                foreach ($expense as $key => $value) {
                    $total_amount += $value['amount'];
                    $expenseTable .= '<tr>
                        <td>' . ($key + 1) . '</td>
                        <td>' . $value['amount'] . '</td>
                        <td>' . $value['description'] . '</td>
                        <td>' . $value['category'] . '</td>
                        <td>' . $value['created_at'] . '</td>
                    </tr>';
                }
                $expenseTable .= '<tr class="table-success fw-bold">
                    <td colspan="2">Total</td>
                    <td colspan="3">' . number_format($total_amount, 2) . '</td>
                </tr>';
            } else {
                $expenseTable .= '<tr><td colspan="5">No expense found</td></tr>';
            }
            $data['expenseTable'] = $expenseTable;

            // Category Summary
            $this->db->select('category, SUM(amount) as total_amount')
                ->from('expense')
                ->where('user_id', $user_id)
                ->group_by('category');
            $category_summary = $this->db->get()->result_array();

            $categoryTable = '';
            if (!empty($category_summary)) {
                foreach ($category_summary as $row) {
                    $categoryTable .= '<tr>
                        <td>' . htmlspecialchars($row['category']) . '</td>
                        <td>₹' . number_format($row['total_amount'], 2) . '</td>
                    </tr>';
                }
            } else {
                $categoryTable .= '<tr><td colspan="2" class="text-center">No data found.</td></tr>';
            }
            $data['categoryTable'] = $categoryTable;
            $data['chart_data'] = json_encode($category_summary);

            $this->load->view('pages/expense/show_expense', $data);
        } else {
            redirect('login');
        }
    }
}
