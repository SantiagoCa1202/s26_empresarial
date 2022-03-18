<?php

class WalletModel extends Mysql
{

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }


  public function selectReportSales($date, $establishment_id, $box_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND s.date BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $box = $this->box_id > 0 ? "AND s.box_id = '$this->box_id'" : "";

    $establishment = $this->establishment_id > 0 ? "AND b.establishment_id = '$this->establishment_id'" : "";


    $where = "
      s.status = 1
      $box
      $establishment
      $date_range
    ";
    $info = "SELECT IFNULL(SUM(amount), 0) as total_products, 
      IFNULL(SUM(discount), 0) as total_discount, 
      IFNULL(SUM(amount*pvp-discount), 0) as total_sale, 
      IFNULL(SUM(amount*pvp), 0) as total_pvp, 
      IFNULL(SUM(amount*cost), 0) as total_cost, 
      IFNULL(SUM((amount*pvp)-discount-(amount*cost)), 0) as total_gain
      FROM (
        SELECT sp.amount, sp.discount, sp.pvp, sp.cost
          FROM sales_products sp
          JOIN sales s
          ON sp.sale_id = s.id
          JOIN boxes b
          ON s.box_id = b.id
          WHERE $where
        UNION ALL
        SELECT sp.amount, sp.discount, sp.pvp, sp.cost
          FROM sales_credits_products sp
          JOIN sales_credits s
          ON sp.sale_id = s.id
          JOIN boxes b
          ON s.box_id = b.id
          WHERE $where
      )x 
      
    ";
    $info_table =  $this->info_table_company($info, $this->db_company);

    return $info_table;
  }

  public function selectReportReturns($date, $establishment_id, $box_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND s.date BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $box = $this->box_id > 0 ? "AND s.box_id = '$this->box_id'" : "";
    $establishment = $this->establishment_id > 0 ? "AND b.establishment_id = '$this->establishment_id'" : "";

    $where = "
      s.status = 2
      $box
      $establishment
      $date_range
    ";

    $info = "SELECT IFNULL(SUM(amount), 0) as total_products, 
      IFNULL(SUM(discount), 0) as total_discount, 
      IFNULL(SUM(amount*pvp-discount), 0) as total_sale, 
      IFNULL(SUM(amount*pvp), 0) as total_pvp, 
      IFNULL(SUM(amount*cost), 0) as total_cost, 
      IFNULL(SUM((amount*pvp)-discount-(amount*cost)), 0) as total_gain
      FROM (
        SELECT sp.amount, sp.discount, sp.pvp, sp.cost
          FROM sales_products sp
          JOIN sales s
          ON sp.sale_id = s.id
          JOIN boxes b
          ON s.box_id = b.id
          WHERE $where
        UNION ALL
        SELECT sp.amount, sp.discount, sp.pvp, sp.cost
          FROM sales_credits_products sp
          JOIN sales_credits s
          ON sp.sale_id = s.id
          JOIN boxes b
          ON s.box_id = b.id
          WHERE $where
      )x 
      
    ";
    $info_table =  $this->info_table_company($info, $this->db_company);

    return $info_table;
  }

  public function selectReportSalesPerCategories($date, $establishment_id, $box_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND s.date BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $box = $this->box_id > 0 ? "AND s.box_id = '$this->box_id'" : "";
    $establishment = $this->establishment_id > 0 ? "AND b.establishment_id = '$this->establishment_id'" : "";


    $where = "
      s.status = 1
      $box
      $establishment
      $date_range
    ";

    $rows = "SELECT DISTINCT c.*, i.class as icon, s.total_products,
    s.total_sale
      FROM categories c
      JOIN s26_empresarial.icons i
      ON c.icon_id = i.id
      LEFT JOIN (SELECT IFNULL(SUM(amount), 0) as total_products, 
        IFNULL(SUM(amount*pvp-discount), 0) as total_sale,
        category_id
        FROM (
          SELECT sp.amount, sp.discount, sp.pvp, sp.cost, sc.category_id
            FROM sales_products sp
            JOIN sales s
            ON sp.sale_id = s.id
            JOIN boxes b
            ON s.box_id = b.id
            JOIN products_variant pv
            ON sp.variant_id = pv.id
            JOIN products p
            ON pv.product_id = p.id
            JOIN subcategories sc
            ON p.category_id = sc.id 
            WHERE $where
          UNION ALL
          SELECT sp.amount, sp.discount, sp.pvp, sp.cost, sc.category_id
            FROM sales_credits_products sp
            JOIN sales_credits s
            ON sp.sale_id = s.id
            JOIN boxes b
            ON s.box_id = b.id
            JOIN products_variant pv
            ON sp.variant_id = pv.id
            JOIN products p
            ON pv.product_id = p.id
            JOIN subcategories sc
            ON p.category_id = sc.id 
            WHERE $where
        )x 
        GROUP BY category_id
      )s
      ON c.id = s.category_id
      ORDER BY c.id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return $items;
  }

  public function selectReportSalesPerPaymentMethod($date, $establishment_id, $box_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND s.date BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $box = $this->box_id > 0 ? "AND s.box_id = '$this->box_id'" : "";
    $establishment = $this->establishment_id > 0 ? "AND b.establishment_id = '$this->establishment_id'" : "";


    $where = "
      s.status = 1 AND
      sp.status = 1
      $box
      $establishment
      $date_range
    ";

    $rows = "SELECT DISTINCT pm.*, s.total_sale
      FROM payment_methods pm
      LEFT JOIN (SELECT IFNULL(SUM(amount), 0) as total_sale, payment_method_id
        FROM (
          SELECT sp.amount, sp.payment_method_id
            FROM sales_payments sp
            JOIN sales s
            ON sp.sale_id = s.id
            JOIN boxes b
            ON s.box_id = b.id
            WHERE $where
          UNION ALL
          SELECT sp.amount, sp.payment_method_id
            FROM sales_credits_payments sp
            JOIN sales_credits s
            ON sp.sale_id = s.id
            JOIN boxes b
            ON s.box_id = b.id
            WHERE $where
        )x 
        GROUP BY payment_method_id
      )s
      ON pm.id = s.payment_method_id
      ORDER BY pm.id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return $items;
  }

  public function selectReportExternalIncomes($date, $establishment_id, $box_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND ei.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $box = $this->box_id > 0 ? "AND eia.box_id = '$this->box_id'" : "";

    $establishment = $this->establishment_id > 0 ? "AND ei.establishment_id = '$this->establishment_id'" : "";


    $where = "
      ei.status = 1
      $box
      $establishment
      $date_range
    ";
    $info = "SELECT IFNULL(SUM(eic.amount),0) as total_cost,
      IFNULL(SUM(eig.amount),0) as total_gain
      FROM external_incomes ei
      LEFT JOIN (SELECT SUM(eia.amount) as amount, external_income_id
        FROM external_incomes_amounts eia
        JOIN external_incomes ei
        ON eia.external_income_id = ei.id
        WHERE $where AND account = 1
        GROUP BY external_income_id

      )eic
      ON ei.id = eic.external_income_id
      LEFT JOIN (SELECT SUM(eia.amount) as amount, external_income_id
        FROM external_incomes_amounts eia
        JOIN external_incomes ei
        ON eia.external_income_id = ei.id
        WHERE $where AND account = 2
        GROUP BY external_income_id
      )eig
      ON ei.id = eig.external_income_id
    ";
    $info_table =  $this->info_table_company($info, $this->db_company);

    return $info_table;
  }

  public function selectReportExpenses($date, $establishment_id, $box_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND e.date BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $box = $this->box_id > 0 ? "AND e.box_id = '$this->box_id'" : "";

    $establishment = $this->establishment_id > 0 ? "AND b.establishment_id = '$this->establishment_id'" : "";


    $where = "
      e.status = 1
      $box
      $establishment
      $date_range
    ";


    $info_box_cost = "SELECT IFNULL(SUM(e.amount),0) as total_box_cost
      FROM expenses e
      JOIN boxes b
      ON e.box_id = b.id
      WHERE $where AND e.account = 1 AND e.bank_account_id IS NULL    
    ";
    $info_table =  $this->info_table_company($info_box_cost, $this->db_company);

    $info_box_gain = "SELECT IFNULL(SUM(e.amount),0) as total_box_gain
      FROM expenses e
      JOIN boxes b
      ON e.box_id = b.id
      WHERE $where AND e.account = 2 AND e.bank_account_id IS NULL    
    ";
    $info_table +=  $this->info_table_company($info_box_gain, $this->db_company);

    $info_bank_cost = "SELECT IFNULL(SUM(e.amount),0) as total_bank_cost
      FROM expenses e
      JOIN boxes b
      ON e.box_id = b.id
      WHERE $where AND e.account = 1 AND e.bank_account_id > 0 
    ";
    $info_table +=  $this->info_table_company($info_bank_cost, $this->db_company);

    $info_bank_gain = "SELECT IFNULL(SUM(e.amount),0) as total_bank_gain
      FROM expenses e
      JOIN boxes b
      ON e.box_id = b.id
      WHERE $where AND e.account = 2 AND e.bank_account_id > 0 
    ";
    $info_table +=  $this->info_table_company($info_bank_gain, $this->db_company);

    return $info_table;
  }

  public function selectReportDeposits($date, $establishment_id, $box_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND d.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $box = $this->box_id > 0 ? "AND dc.box_id = '$this->box_id'" : "";

    $establishment = $this->establishment_id > 0 ? "AND b.establishment_id = '$this->establishment_id'" : "";

    $where = "
      d.status = 1 AND
      dc.status = 1
      $box
      $establishment
      $date_range
    ";
    $info = "SELECT IFNULL(SUM(dc.amount),0) as total_deposits
      FROM deposits_cash dc
      JOIN deposits d
      ON dc.deposit_id = d.id
      JOIN boxes b
      ON dc.box_id = b.id
      WHERE $where

      
    ";
    $info_table =  $this->info_table_company($info, $this->db_company);

    return $info_table;
  }

  public function selectReportBuys($date, $establishment_id, $box_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND date_issue BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $establishment = $this->establishment_id > 0 ? "AND establishment_id = '$this->establishment_id'" : "";

    $where = "
      status = 1
      $establishment
      $date_range
    ";
    $info = "SELECT IFNULL(SUM(total),0) as total_buys
      FROM buys b
      WHERE $where
    ";
    $info_table =  $this->info_table_company($info, $this->db_company);

    return $info_table;
  }

  public function selectReportCustomers($date, $establishment_id, $box_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      status = 1
      $date_range
    ";
    $info = "SELECT COUNT(id) as total_customers
      FROM customers
      WHERE $where
    ";
    $info_table =  $this->info_table_company($info, $this->db_company);

    return $info_table;
  }

  public function selectReportProducts($date, $establishment_id, $box_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      "BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $establishment = $this->establishment_id > 0 ? " = $this->establishment_id" : "";

    $info = "SELECT IFNULL(SUM(pe.amount),0) as entries, 
      SUM(IFNULL(sp.amount, 0)+IFNULL(scp.amount, 0)) as outlets,
      SUM(IFNULL(spr.amount, 0)+IFNULL(scpr.amount, 0)) as returns,
      SUM(IFNULL(pd.amount, 0)) as damageds,
      SUM(IFNULL(sa.amount, 0)) as settings
      FROM products_variant pv
      LEFT JOIN (
        SELECT SUM(pe.amount) as amount, pe.product_variant_id 
        FROM products_entries_variants pe
        JOIN products_variant pv
        ON pe.product_variant_id = pv.id
        WHERE pe.establishment_id $establishment AND pe.created_at $date_range
      )pe
      ON pv.id = pe.product_variant_id
      LEFT JOIN (
        SELECT SUM(sp.amount) as amount, sp.variant_id 
        FROM sales_products sp
        JOIN sales s
        ON sp.sale_id = s.id
        JOIN products_variant pv
        ON sp.variant_id = pv.id
        JOIN boxes b
        ON s.box_id = b.id
        WHERE b.establishment_id $establishment AND s.date $date_range 
      )sp
      ON pv.id = sp.variant_id
      LEFT JOIN (
        SELECT SUM(scp.amount) as amount, scp.variant_id 
        FROM sales_credits_products scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        JOIN products_variant pv
        ON scp.variant_id = pv.id
        JOIN boxes b
        ON sc.box_id = b.id
        WHERE b.establishment_id $establishment AND sc.date $date_range 
      )scp
      ON pv.id = scp.variant_id
      LEFT JOIN (
        SELECT SUM(sp.amount) as amount, sp.variant_id 
        FROM sales_products sp
        JOIN sales s
        ON sp.sale_id = s.id
        JOIN products_variant pv
        ON sp.variant_id = pv.id
        JOIN boxes b
        ON s.box_id = b.id
        WHERE b.establishment_id $establishment AND s.date $date_range AND s.status != 1
      )spr
      ON pv.id = spr.variant_id
      LEFT JOIN (
        SELECT SUM(scp.amount) as amount, scp.variant_id 
        FROM sales_credits_products scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        JOIN products_variant pv
        ON scp.variant_id = pv.id
        JOIN boxes b
        ON sc.box_id = b.id
        WHERE b.establishment_id $establishment AND sc.date $date_range AND sc.status != 1
      )scpr
      ON pv.id = scpr.variant_id
      LEFT JOIN (
        SELECT SUM(pd.amount) as amount, pd.product_variant_id 
        FROM products_damageds pd
        JOIN products_variant pv
        ON pd.product_variant_id = pv.id
        WHERE pd.establishment_id $establishment AND pd.created_at $date_range AND pd.status = 1
      )pd
      ON pv.id = pd.product_variant_id
      LEFT JOIN (
        SELECT SUM(sa.amount) as amount, sa.product_variant_id 
        FROM stock_adjustment sa
        JOIN products_variant pv
        ON sa.product_variant_id = pv.id
        WHERE sa.establishment_id $establishment AND sa.created_at $date_range
      )sa
      ON pv.id = sa.product_variant_id
    ";
    $info_table =  $this->info_table_company($info, $this->db_company);

    return $info_table;
  }
}
