<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * FusionInvoice
 * 
 * A free and open source web based invoicing system
 *
 * @package		FusionInvoice
 * @author		Jesse Terry
 * @copyright	Copyright (c) 2012 - 2013 FusionInvoice, LLC
 * @license		http://www.fusioninvoice.com/license.txt
 * @link		http://www.fusioninvoice.com
 * 
 */

class Payments extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_payments');
    }

    public function index($page = 0)
    {
        $this->mdl_payments->paginate(site_url('payments/index'), $page);
        $payments = $this->mdl_payments->result();

        $this->layout->set(
            array(
                'payments'           => $payments,
                'filter_display'     => TRUE,
                'filter_placeholder' => lang('filter_payments'),
                'filter_method'      => 'filter_payments'
            )
        );

        $this->layout->buffer('content', 'payments/index');
        $this->layout->render();
    }

    public function form($id = NULL)
    {
        if ($this->input->post('btn_cancel'))
        {
            redirect('payments');
        }

        if ($this->mdl_payments->run_validation())
        {
            $id = $this->mdl_payments->save($id);

            $this->load->model('custom_fields/mdl_payment_custom');

            $this->mdl_payment_custom->save_custom($id, $this->input->post('custom'));

            redirect('payments');
        }

        if (!$this->input->post('btn_submit'))
        {
            $prep_form = $this->mdl_payments->prep_form($id);
            
            if ($id and !$prep_form)
            {
                show_404();
            }

            $this->load->model('custom_fields/mdl_payment_custom');

            $payment_custom = $this->mdl_payment_custom->where('payment_id', $id)->get();

            if ($payment_custom->num_rows())
            {
                $payment_custom = $payment_custom->row();

                unset($payment_custom->payment_id, $payment_custom->payment_custom_id);

                foreach ($payment_custom as $key => $val)
                {
                    $this->mdl_payments->set_form_value('custom[' . $key . ']', $val);
                }
            }
        }
        else
        {
            if ($this->input->post('custom'))
            {
                foreach ($this->input->post('custom') as $key => $val)
                {
                    $this->mdl_payments->set_form_value('custom[' . $key . ']', $val);
                }
            }
        }

        $this->load->model('invoices/mdl_invoices');
        $this->load->model('payment_methods/mdl_payment_methods');
        $this->load->model('custom_fields/mdl_custom_fields');

        $open_invoices = $this->mdl_invoices->where('fi_invoice_amounts.invoice_balance >', 0)->get()->result();

        $amounts = array();

        foreach ($open_invoices as $open_invoice)
        {
            $amounts['invoice' . $open_invoice->invoice_id] = format_amount($open_invoice->invoice_balance);
        }
        
        $this->layout->set(
            array(
                'payment_id'      => $id,
                'payment_methods' => $this->mdl_payment_methods->get()->result(),
                'open_invoices'   => $open_invoices,
                'custom_fields'   => $this->mdl_custom_fields->by_table('fi_payment_custom')->get()->result(),
                'amounts'         => json_encode($amounts)
            )
        );

        if ($id)
        {
            $this->layout->set('payment', $this->mdl_payments->where('fi_payments.payment_id', $id)->get()->row());
        }

        $this->layout->buffer('content', 'payments/form');
        $this->layout->render();
    }

    public function delete($id)
    {
        $this->mdl_payments->delete($id);
        redirect('payments');
    }

}

?>