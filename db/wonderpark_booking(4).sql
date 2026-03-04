-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2025 at 02:33 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wonderpark_booking`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `--sp_GetRoomAvailability` (IN `room_id` INT, IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    -- Create a temporary table to hold the date range
    CREATE TEMPORARY TABLE date_range (date DATE);
    
    -- Populate the date range table using a loop
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        INSERT INTO date_range (date) VALUES (@current_date);
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Check room availability for the given room across the date range
    SELECT 
        dr.date,
        r.id AS room_id,
        r.room_number, 
        r.floor,
        (SELECT MIN(rd.image_path) 
         FROM room_details rd 
         WHERE rd.room_id = r.id) AS image_path, -- Select the first image_path
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM bookings b
                WHERE b.room_id = r.id
                  AND b.check_in_date <= dr.date
                  AND b.check_out_date > dr.date
                  AND b.Booking_status != 2 -- ✅ Only check bookings where status is NOT 2
            ) THEN 'Booked'
            ELSE 'Available'
        END AS is_booked
    FROM 
        date_range dr
    CROSS JOIN (
        SELECT 
            rooms.id, 
            rooms.room_number, 
            rooms.floor
        FROM rooms
        WHERE rooms.id = room_id -- Filter for the specific room
    ) r
    ORDER BY dr.date;

    -- Drop the temporary table
    DROP TEMPORARY TABLE date_range;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CompleteServiceList` (IN `startDate` DATE, IN `endDate` DATE)   BEGIN
    WITH salesService AS (
        SELECT 
            psd.id as psd_id,
            ps.invoice_no AS invoice_no,
        	psd.service_invoice AS service_invoice,
            fa.account_name AS customer_name,
            p.product_name AS product_name,
            psd.service_date AS service_date,
            psd.actual_service_date AS actual_service_date,
            psd.service_number AS service_number,
            psd.service_status AS service_status,
            ps.done_by AS done_by
        FROM 
            product_services AS ps
        JOIN 
            product_service_details AS psd ON ps.id = psd.product_service_id
        JOIN 
            products AS p ON ps.product_id = p.id
        JOIN 
            finance_accounts AS fa ON ps.customer_id = fa.id
        WHERE 
            psd.service_status = 1
        AND
            psd.actual_service_date BETWEEN startDate AND endDate
    )
    SELECT 
        psd_id,
        invoice_no,
        service_invoice,
        customer_name,
        product_name,
        service_date,
        actual_service_date,
        service_number,
        service_status,
        done_by
    FROM 
        salesService
    ORDER BY 
        actual_service_date;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_DamageProductDateWise` (IN `CDate1` DATE, IN `CDate2` DATE, IN `CWarehouseId` INT, IN `CProductId` INT)   BEGIN
    SELECT 
        dp.id, 
        dp.damage_no, 
        p.product_name, 
        w.name, 
        fa.account_name as supplier_name,  
        dp.purchasePrice, 
        dp.salePrice, 
        dp.damage_quantity, 
        dp.damage_reason, 
        dp.damage_date, 
        dp.is_exchangeable, 
        dp.is_repairable, 
        dp.is_resaleable, 
        dp.status, 
        dp.done_by, 
        dp.created_at, 
        dp.updated_at
    FROM 
        damage_products dp
    LEFT JOIN 
        products p ON dp.product_id = p.id
    LEFT JOIN 
        warehouses w ON dp.warehouse_id = w.id
    LEFT JOIN 
        finance_accounts fa ON dp.supplier_id = fa.id
    WHERE 
        dp.damage_date BETWEEN CDate1 AND CDate2
        AND (CWarehouseId IS NULL OR dp.warehouse_id = CWarehouseId)
        AND (CProductId IS NULL OR dp.product_id = CProductId);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GeneralLedgerByAccount` (IN `ACCID` INT, IN `CDate1` DATE, IN `CDate2` DATE, IN `PDate1` DATE, IN `PDate2` DATE)   BEGIN

  SELECT
        '' AS `date`,
        company_code AS `company_code`,
        '' AS `voucher_no`,
        '' AS `to_acc_name`,
  CASE
            WHEN (
                COALESCE(
                    (
                        SUM(
                            CASE
                                WHEN balance_type = 'Dr' THEN amount
                                ELSE 0
                            END
                        ) - SUM(
                            CASE
                                WHEN balance_type = 'Cr' THEN amount
                                ELSE 0
                            END
                        )
                    ),
                    0
                ) < 0
            ) THEN (
                COALESCE(
                    (
                        SUM(
                            CASE
                                WHEN balance_type = 'Dr' THEN amount
                                ELSE 0
                            END
                        ) - SUM(
                            CASE
                                WHEN balance_type = 'Cr' THEN amount
                                ELSE 0
                            END
                        )
                    ),
                    0
                ) * -1
            )
            ELSE COALESCE(
                (
                    SUM(
                        CASE
                            WHEN balance_type = 'Dr' THEN amount
                            ELSE 0
                        END
                    ) - SUM(
                        CASE
                            WHEN balance_type = 'Cr' THEN amount
                            ELSE 0
                        END
                    )
                ),
                0
            )
        END  AS amount,
    
    
        CASE
            WHEN (
                COALESCE(
                    (
                        SUM(
                            CASE
                                WHEN balance_type = 'Dr' THEN amount
                                ELSE 0
                            END
                        ) - SUM(
                            CASE
                                WHEN balance_type = 'Cr' THEN amount
                                ELSE 0
                            END
                        )
                    ),
                    0
                ) < 0
            ) THEN 'Cr'
            ELSE 'Dr'
        END AS `balance_type`,
        CONCAT(
            'Opening Balance as on : ',
            DATE_FORMAT(PDate2, '%d-%m-%Y')
        ) AS `narration`,
        '' AS `cheque_no`,
        '' AS `cheque_date`,
        '' AS `cheque_type`,
        '' AS `type`
    FROM
        finance_transactions
    WHERE
        acid = ACCID
        AND voucher_date BETWEEN PDate1 AND PDate2
    GROUP BY
        company_code

    UNION ALL

    SELECT
        DATE_FORMAT(voucher_date, '%d-%m-%Y') AS `Date`,
        company_code AS `company_code`,
        voucher_no AS `voucher_no`,
        to_acc_name AS to_acc_name,
        amount AS `amount`,
        balance_type AS `balance_type`,
        narration AS `narration`,
        cheque_no AS `cheque_no`,
        CASE
            WHEN cheque_date = '0001-01-01' THEN ''
            ELSE DATE_FORMAT(cheque_date, '%d-%m-%Y')
        END AS `cheque_date`,
        cheque_type AS `cheque_type`,
        type AS `type`
    FROM
        finance_transactions 
    WHERE
        acid = ACCID
        AND voucher_date BETWEEN CDate1 AND CDate2;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetBookingCheck` (IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    -- Create a temporary table to hold the date range
    CREATE TEMPORARY TABLE date_range (date DATE);
    
    -- Populate the date range table using a recursive CTE simulation
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        INSERT INTO date_range (date) VALUES (@current_date);
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Generate the room availability report including room_number and image
    SELECT 
        dr.date,
        r.id AS room_id,
        r.room_number, 
        r.floor,
        rd.image_path, -- Include the image path from the room_details table
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM bookings b
                WHERE b.room_id = r.id
                  AND b.check_in_date <= dr.date
                  AND DATE_SUB(b.check_out_date, INTERVAL 1 DAY) >= dr.date -- Adjusted here
            ) THEN 'Booked'
            ELSE 'Available'
        END AS is_booked
    FROM 
        date_range dr
    CROSS JOIN (
        SELECT 
            rooms.id, 
            rooms.room_number, 
            rooms.floor
        FROM rooms
    ) r
    LEFT JOIN room_details rd ON r.id = rd.room_id -- Join with room_details table
    ORDER BY dr.date, r.id;

    -- Drop the temporary table
    DROP TEMPORARY TABLE date_range;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetBookingCheck_` (IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    -- Create a temporary table to hold the date range
    CREATE TEMPORARY TABLE date_range (date DATE);

    -- Populate the date range table using a recursive CTE simulation
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        INSERT INTO date_range (date) VALUES (@current_date);
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Generate the room availability report including room_number
    SELECT 
        dr.date,
        r.id,
        r.room_number, -- Include room_number from the rooms table
        r.floor,
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM bookings b
                WHERE b.room_id = r.id 
                  AND b.check_in_date <= dr.date 
                  AND b.check_out_date >= dr.date
            ) THEN 'Booked'
            ELSE 'Available'
        END AS is_booked
    FROM 
        date_range dr
    CROSS JOIN (
        SELECT 
            rooms.id, 
            rooms.room_number, -- Fetch room_number along with room_id
        	rooms.floor
        FROM rooms
    ) r
    ORDER BY dr.date, r.id;

    -- Drop the temporary table
    DROP TEMPORARY TABLE date_range;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetBookingCheck_2` (IN `start_date` DATE, IN `end_date` DATE)   BEGIN  
    -- Create a temporary table to hold the date range  
    CREATE TEMPORARY TABLE date_range (date DATE);  

    -- Populate the date range table using a loop  
    SET @current_date = start_date;  
    WHILE @current_date <= end_date DO  
        INSERT INTO date_range (date) VALUES (@current_date);  
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);  
    END WHILE;  

    -- Generate the room availability report including room_number and the first image_path  
    SELECT   
        dr.date,  
        r.id AS room_id,  
        r.room_number,   
        r.floor,  
        (SELECT MIN(rd.image_path)   
         FROM room_details rd   
         WHERE rd.room_id = r.id) AS image_path, -- Select the first image_path  
        CASE   
            WHEN EXISTS (  
                SELECT 1   
                FROM bookings b  
                WHERE b.room_id = r.id  
                AND b.check_in_date <= dr.date  
                AND b.check_out_date > dr.date  -- ✅ Include end_date without subtracting 1 day  
                AND b.Booking_status != 2 -- ✅ Ignore canceled bookings (Booking_status = 2)
            ) THEN 'Booked'  
            ELSE 'Available'  
        END AS is_booked  
    FROM   
        date_range dr  
    CROSS JOIN (  
        SELECT   
            rooms.id,   
            rooms.room_number,   
            rooms.floor  
        FROM rooms  
    ) r  
    ORDER BY dr.date, r.id;  

    -- Drop the temporary table  
    DROP TEMPORARY TABLE date_range;  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetBookingCheck_last_ok` (IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    -- Create a temporary table to hold the date range
    CREATE TEMPORARY TABLE date_range (date DATE);
    
    -- Populate the date range table using a recursive CTE simulation
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        INSERT INTO date_range (date) VALUES (@current_date);
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Generate the room availability report including room_number
    SELECT 
        dr.date,
        r.id,
        r.room_number, -- Include room_number from the rooms table
        r.floor,
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM bookings b
                WHERE b.room_id = r.id
                  AND b.check_in_date <= dr.date
                  AND DATE_SUB(b.check_out_date, INTERVAL 1 DAY) >= dr.date -- Adjusted here
            ) THEN 'Booked'
            ELSE 'Available'
        END AS is_booked
    FROM 
        date_range dr
    CROSS JOIN (
        SELECT 
            rooms.id, 
            rooms.room_number, -- Fetch room_number along with room_id
            rooms.floor
        FROM rooms
    ) r
    ORDER BY dr.date, r.id;

    -- Drop the temporary table
    DROP TEMPORARY TABLE date_range;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetBookingCheck__` (IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    -- Create a temporary table to hold the date range
    CREATE TEMPORARY TABLE date_range (date DATE);

    -- Populate the date range table using a recursive CTE simulation
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        INSERT INTO date_range (date) VALUES (@current_date);
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Generate the room availability report
    SELECT 
        dr.date,
        r.room_id,
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM bookings b
                WHERE b.room_id = r.room_id 
                  AND b.check_in_date <= dr.date 
                  AND b.check_out_date >= dr.date
            ) THEN 1
            ELSE 0
        END AS is_booked
    FROM 
        date_range dr
    CROSS JOIN (SELECT DISTINCT room_id FROM bookings) r
    ORDER BY dr.date, r.room_id;

    -- Drop the temporary table
    DROP TEMPORARY TABLE date_range;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetRoomAvailability` (IN `room_id` INT, IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    -- Create a temporary table to hold the date range
    CREATE TEMPORARY TABLE date_range (date DATE);
    
    -- Populate the date range table using a loop
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        INSERT INTO date_range (date) VALUES (@current_date);
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Check room availability for the given room across the date range
    SELECT 
        dr.date,
        r.id AS room_id,
        r.room_number, 
        r.floor,
        (SELECT MIN(rd.image_path) 
         FROM room_details rd 
         WHERE rd.room_id = r.id) AS image_path, -- Select the first image_path
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM bookings b
                WHERE b.room_id = r.id
                  AND b.check_in_date <= dr.date
                  AND b.check_out_date > dr.date
                  AND b.Booking_status != 2 -- ✅ Only check bookings where status is NOT 2
            ) THEN 'Booked'
            ELSE 'Available'
        END AS is_booked
    FROM 
        date_range dr
    CROSS JOIN (
        SELECT 
            rooms.id, 
            rooms.room_number, 
            rooms.floor
        FROM rooms
        WHERE rooms.id = room_id -- Filter for the specific room
    ) r
    ORDER BY dr.date;

    -- Drop the temporary table
    DROP TEMPORARY TABLE date_range;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetStockTransferDetailsByInvoice` (IN `invoiceNo` VARCHAR(50))   BEGIN
    SELECT
    	s.id AS stock_id,
        s.stock_date AS stock_date,
        s.invoice_no AS invoice_no,
        s.warehouse_id AS from_warehouse_id,
        s.to_warehouse_id AS to_warehouse_id,
        s.product_id AS product_id,
        fw.name AS from_warehouse_name,
        tw.name AS to_warehouse_name,
        p.product_name AS product_name,
        s.stock_out_quantity AS quantity,
        s.stock_out_unit_price AS unit_price,
        s.stock_out_total_amount AS total_amount,
        s.status AS product_status
    FROM 
        stocks AS s
    JOIN products AS p ON s.product_id = p.id
    JOIN warehouses AS fw ON s.warehouse_id = fw.id
    JOIN warehouses AS tw ON s.to_warehouse_id = tw.id
    WHERE 
        s.invoice_no = invoiceNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetStockTransferList` (IN `startDate` DATE, IN `endDate` DATE, IN `warehouse_id` INT, IN `status` INT)   BEGIN
    SELECT
        s.stock_date AS stock_date,
        s.invoice_no AS invoice_no,
        fw.name AS from_warehouse_name,
        tw.name AS to_warehouse_name,
        s.status AS status
    FROM 
        stocks AS s
    JOIN products AS p ON s.product_id = p.id
    JOIN warehouses AS fw ON s.warehouse_id = fw.id
    JOIN warehouses AS tw ON s.to_warehouse_id = tw.id
    WHERE 
        (s.warehouse_id = warehouse_id OR s.to_warehouse_id = warehouse_id OR warehouse_id = 0)
        AND s.stock_date BETWEEN startDate AND endDate
        AND s.invoice_no LIKE 'TRN%'
        AND s.status = status
    GROUP BY s.invoice_no;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetStockTransferPendingList` (IN `startDate` DATE, IN `endDate` DATE, IN `warehouseId` INT)   BEGIN
    SELECT
        s.stock_date AS stock_date,
        s.invoice_no AS invoice_no,
        fw.name AS from_warehouse_name,
        tw.name AS to_warehouse_name,
        s.status AS status
    FROM 
        stocks AS s
    JOIN products AS p ON s.product_id = p.id
    JOIN warehouses AS fw ON s.warehouse_id = fw.id
    JOIN warehouses AS tw ON s.to_warehouse_id = tw.id
    WHERE 
        (s.warehouse_id = warehouseId OR s.to_warehouse_id = warehouseId OR warehouseId = 0)
        AND s.stock_date BETWEEN startDate AND endDate
        AND s.invoice_no LIKE 'TRN%'
        AND s.status = 0
    GROUP BY 
        s.invoice_no;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetTotalSummeryReport` (IN `startDate` DATE, IN `endDate` DATE)   SELECT 
    FORMAT(IFNULL(SUM(s.stock_in_total_amount), 0.00), 2) AS total_purchase_amount,
    FORMAT(IFNULL(SUM(s.stock_out_total_amount), 0.00), 2) AS total_sales_amount,
    FORMAT(
        IFNULL(
            (SELECT SUM(ft.amount) 
             FROM finance_accounts fa
             JOIN finance_transactions ft ON fa.id = ft.acid 
             WHERE fa.account_group_code LIKE '2%' 
               AND fa.id != 13 
               AND ft.voucher_date BETWEEN startDate AND endDate),
            0.00
        ), 2
    ) AS total_expense_amount,
    FORMAT(
        IFNULL(
            (SELECT SUM(TotalValue)
             FROM (
                 SELECT
                    product_id, 
                    stock_in_unit_price AS Price, 
                    (SUM(CASE WHEN stock_date BETWEEN '1900' AND endDate THEN stock_in_quantity ELSE 0 END) 
                     - SUM(CASE WHEN stock_date BETWEEN '1900' AND endDate THEN stock_out_quantity ELSE 0 END)) AS Qty, 
                    (stock_in_unit_price * 
                     (SUM(CASE WHEN stock_date BETWEEN '1900' AND endDate THEN stock_in_quantity ELSE 0 END) 
                      - SUM(CASE WHEN stock_date BETWEEN '1900' AND endDate THEN stock_out_quantity ELSE 0 END))) AS TotalValue
                 FROM stocks
                 GROUP BY product_id, stock_in_unit_price
             ) AS ProductTotals
            ), 0.00
        ), 2
    ) AS current_stock_amount
FROM stocks AS s
WHERE s.stock_date BETWEEN startDate AND endDate$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetTotalSummeryReport_old` (IN `startDate` DATE, IN `endDate` DATE)   SELECT 
    FORMAT(IFNULL(SUM(s.stock_in_total_amount), 0.00), 2) AS total_purchase_amount,
    FORMAT(IFNULL(SUM(s.stock_out_total_amount), 0.00), 2) AS total_sales_amount,
    FORMAT(
        IFNULL(
            (SELECT SUM(ft.amount) 
             FROM finance_accounts fa
             JOIN finance_transactions ft ON fa.id = ft.acid 
             WHERE fa.account_group_code LIKE '2%' 
               AND fa.id != 13 
               AND ft.voucher_date BETWEEN startDate AND endDate),
            0.00
        ), 2
    ) AS total_expense_amount,
    FORMAT(
        IFNULL(
            (SELECT SUM(TotalValue)
             FROM (
                 SELECT
                    product_id, 
                    stock_in_unit_price AS Price, 
                    (SUM(stock_in_quantity) - SUM(stock_out_quantity)) AS Qty, 
                    (stock_in_unit_price * (SUM(stock_in_quantity) - SUM(stock_out_quantity))) AS TotalValue
                 FROM stocks
                 GROUP BY product_id, stock_in_unit_price
             ) AS ProductTotals
            ), 0.00
        ), 2
    ) AS current_stock_amount
FROM stocks AS s
WHERE s.stock_date BETWEEN startDate AND endDate$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_PendingServiceList` (IN `startDate` DATE, IN `endDate` DATE)   BEGIN
    WITH salesService AS (
        SELECT 
            psd.id as psd_id,
            ps.invoice_no AS invoice_no,
            fa.account_name AS customer_name,
            p.product_name AS product_name,
            psd.service_date AS service_date,
            psd.service_number AS service_number,
            psd.service_status AS service_status,
            ps.done_by AS done_by
        FROM 
            product_services AS ps
        JOIN 
            product_service_details AS psd ON ps.id = psd.product_service_id
        JOIN 
            products AS p ON ps.product_id = p.id
        JOIN 
            finance_accounts AS fa ON ps.customer_id = fa.id
        WHERE 
            psd.service_status = 0
        AND
            psd.service_date BETWEEN startDate AND endDate
    )
    SELECT 
        psd_id,
        invoice_no,
        customer_name,
        product_name,
        service_date,
        service_number,
        service_status,
        done_by
    FROM 
        salesService
    ORDER BY 
        service_date;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SalesProfitInvoiceWise` (IN `startDate` DATE, IN `endDate` DATE)   BEGIN
    WITH sales AS (
        SELECT 
            st.invoice_no,
            DATE(MIN(st.stock_date)) AS sales_date,
            SUM(st.stock_out_quantity) AS quantity,
            ROUND(SUM(st.stock_out_quantity * st.purchase_price), 3) AS net_purchase_price,
            ROUND(SUM(st.stock_out_discount), 3) AS net_discount,
            ROUND(SUM(st.stock_out_total_amount), 3) AS net_sales_price,
            ROUND(SUM(st.stock_out_total_amount - (st.stock_out_quantity * st.purchase_price)), 3) AS net_profit
        FROM 
            stocks AS st
        JOIN 
            products AS p ON st.product_id = p.id
        WHERE 
            st.stock_date BETWEEN startDate AND endDate
            AND st.invoice_no LIKE 'INV%'
        GROUP BY 
            st.invoice_no
    )
    
    SELECT 
        invoice_no,
        sales_date,
        quantity,
        net_purchase_price,
        net_discount,
        net_sales_price,
        net_profit
    FROM 
        sales
    ORDER BY 
        invoice_no;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SalesProfitItemWise` (IN `startDate` DATE, IN `endDate` DATE, IN `givenProductID` INT)   BEGIN
    WITH avg_purchase AS (
        SELECT 
            st.product_id,
            ROUND(SUM(st.stock_in_total_amount) / NULLIF(SUM(st.stock_in_quantity), 0), 3) AS avg_purchase_price
        FROM 
            stocks AS st 
        WHERE 
            st.invoice_no LIKE 'PUR%'
        GROUP BY 
            st.product_id
    ),
    sales AS (
        SELECT 
            st.product_id,
            SUM(st.stock_out_quantity) AS total_sales_quantity,
            SUM(st.stock_out_total_amount) AS total_sales_amount
        FROM 
            stocks AS st
        WHERE 
            st.stock_date BETWEEN startDate AND endDate
            AND (st.product_id = givenProductID OR givenProductID = 0)
            AND st.invoice_no LIKE 'INV%'
        GROUP BY 
            st.product_id
    )
    
    SELECT 
        sa.product_id,
        p.product_name,
        sa.total_sales_quantity,
        ap.avg_purchase_price,
        ROUND((sa.total_sales_quantity * ap.avg_purchase_price), 3) AS total_purchase_amount,
        ROUND((sa.total_sales_amount / NULLIF(sa.total_sales_quantity, 0)), 3) AS avg_sales_price,
        ROUND(sa.total_sales_amount, 3) AS total_sales_amount,
        ROUND((sa.total_sales_amount - (sa.total_sales_quantity * ap.avg_purchase_price)), 3) AS total_profit
    FROM 
        sales AS sa
    JOIN 
        avg_purchase AS ap ON sa.product_id = ap.product_id
    JOIN 
        products AS p ON sa.product_id = p.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_StockReportFinishGoodWise` (IN `CDate1` DATE, IN `CDate2` DATE, IN `CWarehouseId` INT, IN `CProductId` INT)   BEGIN
    -- Declare local variables
    DECLARE Date1 DATE;
    DECLARE Date2 DATE;
    DECLARE WarehouseId INT;
    DECLARE ProductId INT;

    -- Assign the procedure parameters to local variables
    SET Date1 = CDate1;
    SET Date2 = CDate2;
    SET WarehouseId = CWarehouseId;
    SET ProductId = CProductId;

    -- Main query to select product stock report with filtering
    SELECT
        products.id,
        products.product_name,
        products.unit_id,
        product_units.unit_name AS unit_id,
        IFNULL(warehouses.name, 'No Warehouse') AS warehouse_name,
        
        -- Calculate the pre-quantity (before Date1) for each warehouse
        IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_in_quantity) - SUM(stocks.stock_out_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN '1900-01-01' AND DATE_SUB(Date1, INTERVAL 1 DAY)
        ), 0) AS PreQty,
        
        -- Calculate the stock-in quantity between Date1 and Date2
        IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_in_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN Date1 AND Date2
        ), 0) AS InQty,
        
        -- Calculate the total stock quantity (PreQty + InQty)
        IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_in_quantity) - SUM(stocks.stock_out_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN '1900-01-01' AND DATE_SUB(Date1, INTERVAL 1 DAY)
        ), 0) + IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_in_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN Date1 AND Date2
        ), 0) AS TotQty,
        
        -- Calculate the stock-out quantity between Date1 and Date2
        IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_out_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN Date1 AND Date2
        ), 0) AS OutQty
    FROM
        products
    LEFT JOIN stocks ON products.id = stocks.product_id
    LEFT JOIN product_units ON product_units.id = products.unit_id
    LEFT JOIN warehouses ON stocks.warehouse_id = warehouses.id
    WHERE
        products.type_id = 1
        AND products.status = 1
        AND (ProductId = 0 OR products.id = ProductId)
        AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
    GROUP BY
        products.id,
        products.product_name,
        products.unit_id,
        warehouses.id,
        warehouses.name
    ORDER BY
        products.id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_StockReportFinishGoodWise_old` (IN `CDate1` DATE, IN `CDate2` DATE)   BEGIN

SET @Date1 = CDate1;
SET @Date2 = CDate2;


SELECT
        products.id,
        products.product_name,
        products.unit_id,
        product_units.unit_name as unit_id,
        IFNULL(
            (
                SELECT
                    ROUND(
                        (
                            SUM(stocks.stock_in_quantity) - SUM(stocks.stock_out_quantity)
                        ),
                        2
                    )
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN '1900-01-01' AND DATE_SUB(@Date1, INTERVAL 1 DAY)
            ),
            0
        ) AS PreQty,
        IFNULL(
            (
                SELECT
                    ROUND(SUM(stocks.stock_in_quantity), 2)
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN @Date1 AND @Date2
            ),
            0
        ) AS InQty,
        IFNULL(
            (
                SELECT
                    ROUND(
                        (
                            SUM(stocks.stock_in_quantity) - SUM(stocks.stock_out_quantity)
                        ),
                        2
                    )
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN '1900-01-01' AND DATE_SUB(@Date1, INTERVAL 1 DAY)
            ),
            0
        ) + IFNULL(
            (
                SELECT
                    ROUND(SUM(stocks.stock_in_quantity), 2)
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN @Date1 AND @Date2
            ),
            0
        ) AS TotQty,
        IFNULL(
            (
                SELECT
                    ROUND(SUM(stocks.stock_out_quantity), 2)
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN @Date1 AND @Date2
            ),
            0
        ) AS OutQty
    FROM
        products
    LEFT JOIN stocks ON products.id = stocks.product_id
    LEFT JOIN product_units on product_units.id=products.unit_id
    WHERE products.type_id = 1 AND products.status= 1
    GROUP BY
        products.id,
        products.product_name,
        products.unit_id
    ORDER BY
        products.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_StockReportItemWise` (IN `CDate1` DATE, IN `CDate2` DATE, IN `CProductID` INT)   BEGIN

    SET @Date1 = CDate1;
    SET @Date2 = CDate2;
    SET @ProductID = CProductID;

    -- Temporary table to store opening balance
    CREATE TEMPORARY TABLE temp_opening_balance AS
    SELECT
        products.id AS product_id,
        IFNULL(SUM(stocks.stock_in_quantity), 0) AS OpeningInQty,
        IFNULL(SUM(stocks.stock_out_quantity), 0) AS OpeningOutQty
    FROM
        products
    LEFT JOIN stocks ON products.id = stocks.product_id
    WHERE
        products.id = @ProductID
        AND date(stocks.stock_date) < @Date1
    GROUP BY
        products.id;

    -- First part: Opening balance
    SELECT
        NULL AS product_id,
        NULL AS product_name,
        NULL AS unit_id,
        NULL AS unit_name,
        NULL AS invoice_no,
        NULL AS stock_date,
        NULL AS InQty,
        NULL AS OutQty,
        IFNULL((
            SELECT
                OpeningInQty - OpeningOutQty
            FROM
                temp_opening_balance
        ), 0) AS OpeningBalance
    UNION ALL
    -- Second part: Current period data
    SELECT
        products.id AS product_id,
        products.product_name,
        products.unit_id,
        product_units.unit_name AS unit_name,
        stocks.invoice_no,
        stocks.stock_date,
        IFNULL(SUM(stocks.stock_in_quantity), 0) AS InQty,
        IFNULL(SUM(stocks.stock_out_quantity), 0) AS OutQty,
        NULL AS OpeningBalance
    FROM
        products
    LEFT JOIN stocks ON products.id = stocks.product_id
    LEFT JOIN product_units ON product_units.id = products.unit_id
    WHERE
        products.id = @ProductID
        AND date(stocks.stock_date) BETWEEN @Date1 AND @Date2
    GROUP BY
        products.id,
        products.product_name,
        products.unit_id,
        product_units.unit_name,
        stocks.invoice_no,
        stocks.stock_date

    -- Ensure the entire result set is ordered by stock_date
    ORDER BY
        stock_date;

    -- Drop the temporary table after use
    DROP TEMPORARY TABLE IF EXISTS temp_opening_balance;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_StockReportMaterialWise` (IN `CDate1` DATE, IN `CDate2` DATE, IN `CWarehouseId` INT, IN `CProductId` INT)   BEGIN
    -- Declare local variables
    DECLARE Date1 DATE;
    DECLARE Date2 DATE;
    DECLARE WarehouseId INT;
    DECLARE ProductId INT;

    -- Assign the procedure parameters to local variables
    SET Date1 = CDate1;
    SET Date2 = CDate2;
    SET WarehouseId = CWarehouseId;
    SET ProductId = CProductId;

    -- Main query to select product stock report with filtering
    SELECT
        products.id,
        products.product_name,
        products.unit_id,
        product_units.unit_name AS unit_id,
        IFNULL(warehouses.name, 'No Warehouse') AS warehouse_name,
        
        -- Calculate the pre-quantity (before Date1) for each warehouse
        IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_in_quantity) - SUM(stocks.stock_out_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN '1900-01-01' AND DATE_SUB(Date1, INTERVAL 1 DAY)
        ), 0) AS PreQty,
        
        -- Calculate the stock-in quantity between Date1 and Date2
        IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_in_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN Date1 AND Date2
        ), 0) AS InQty,
        
        -- Calculate the total stock quantity (PreQty + InQty)
        IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_in_quantity) - SUM(stocks.stock_out_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN '1900-01-01' AND DATE_SUB(Date1, INTERVAL 1 DAY)
        ), 0) + IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_in_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN Date1 AND Date2
        ), 0) AS TotQty,
        
        -- Calculate the stock-out quantity between Date1 and Date2
        IFNULL((
            SELECT
                ROUND(SUM(stocks.stock_out_quantity), 2)
            FROM
                stocks
            WHERE
                stocks.product_id = products.id
                AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
                AND stocks.warehouse_id = warehouses.id -- Ensure it's specific to the warehouse
                AND DATE(stocks.stock_date) BETWEEN Date1 AND Date2
        ), 0) AS OutQty
    FROM
        products
    LEFT JOIN stocks ON products.id = stocks.product_id
    LEFT JOIN product_units ON product_units.id = products.unit_id
    LEFT JOIN warehouses ON stocks.warehouse_id = warehouses.id
    WHERE
        products.type_id = 2
        AND products.status = 1
        AND (ProductId = 0 OR products.id = ProductId)
        AND (WarehouseId = 0 OR stocks.warehouse_id = WarehouseId)
    GROUP BY
        products.id,
        products.product_name,
        products.unit_id,
        warehouses.id,
        warehouses.name
    ORDER BY
        products.id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_StockReportMaterialWise_old` (IN `CDate1` DATE, IN `CDate2` DATE)   BEGIN

SET @Date1 = CDate1;
SET @Date2 = CDate2;



SELECT
        products.id,
        products.product_name,
        product_units.unit_name as unit_id,
        IFNULL(
            (
                SELECT
                    ROUND(
                        (
                            SUM(stocks.stock_in_quantity) - SUM(stocks.stock_out_quantity)
                        ),
                        2
                    )
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN '1900-01-01' AND DATE_SUB(@Date1, INTERVAL 1 DAY)
            ),
            0
        ) AS PreQty,
        IFNULL(
            (
                SELECT
                    ROUND(SUM(stocks.stock_in_quantity), 2)
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN @Date1 AND @Date2
            ),
            0
        ) AS InQty,
        IFNULL(
            (
                SELECT
                    ROUND(
                        (
                            SUM(stocks.stock_in_quantity) - SUM(stocks.stock_out_quantity)
                        ),
                        2
                    )
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                   AND date(stocks.stock_date) BETWEEN '1900-01-01' AND DATE_SUB(@Date1, INTERVAL 1 DAY)
            ),
            0
        ) + IFNULL(
            (
                SELECT
                    ROUND(SUM(stocks.stock_in_quantity), 2)
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN @Date1 AND @Date2
            ),
            0
        ) AS TotQty,
        
        IFNULL(
            (
                SELECT
                    ROUND(SUM(stocks.stock_out_quantity), 2)
                FROM
                    stocks
                WHERE
                    stocks.product_id = products.id
                    AND date(stocks.stock_date) BETWEEN @Date1 AND @Date2
            ),
            0
        ) AS OutQty
    FROM
        products
    LEFT JOIN stocks ON products.id = stocks.product_id
    LEFT JOIN product_units on product_units.id=products.unit_id
    WHERE products.type_id = 2 AND products.status= 1
    GROUP BY
        products.id,
        products.product_name,
        products.unit_id
    ORDER BY
        products.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_TotalProfit` (IN `startDate` DATE, IN `endDate` DATE)   BEGIN
SELECT 
    id,
    stock_date,
    stock_type,
    invoice_no,
    purchase_price,
    stock_out_quantity,
    stock_out_unit_price,
    FORMAT((purchase_price * stock_out_quantity), 3) AS purchase_sub_total,
    stock_out_total_amount AS stock_out_sub_total_amount,
    FORMAT((stock_out_total_amount - (purchase_price * stock_out_quantity)), 3) AS profit,    
    (SELECT FORMAT(SUM((purchase_price * stock_out_quantity)), 3) 
     FROM stocks WHERE stock_type = 'Out' AND stock_date BETWEEN startDate AND endDate) AS total_purchase,
     (SELECT FORMAT(SUM((stock_out_total_amount)), 3) 
     FROM stocks WHERE stock_type = 'Out' AND stock_date BETWEEN startDate AND endDate) AS total_sale,
    (SELECT FORMAT(SUM(stock_out_total_amount - (purchase_price * stock_out_quantity)), 3) 
     FROM stocks WHERE stock_type = 'Out' AND stock_date BETWEEN startDate AND endDate) AS total_profit
FROM 
    stocks 
WHERE 
    stock_type = 'Out' AND stock_date BETWEEN startDate AND endDate
ORDER BY `stocks`.`invoice_no` DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `_o_sp_GetRoomAvailability` (IN `room_id` INT, IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    -- Create a temporary table to hold the date range
    CREATE TEMPORARY TABLE date_range (date DATE);
    
    -- Populate the date range table using a recursive simulation
    SET @current_date = start_date;
    WHILE @current_date <= end_date DO
        INSERT INTO date_range (date) VALUES (@current_date);
        SET @current_date = DATE_ADD(@current_date, INTERVAL 1 DAY);
    END WHILE;

    -- Check room availability for the given room across the date range
    SELECT 
        dr.date,
        r.id AS room_id,
        r.room_number, 
        r.floor,
        (SELECT MIN(rd.image_path) 
         FROM room_details rd 
         WHERE rd.room_id = r.id) AS image_path, -- Select the first image_path
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM bookings b
                WHERE b.room_id = r.id
                  AND b.check_in_date <= dr.date
                  AND DATE_SUB(b.check_out_date, INTERVAL 1 DAY) >= dr.date
            ) THEN 'Booked'
            ELSE 'Available'
        END AS is_booked
    FROM 
        date_range dr
    CROSS JOIN (
        SELECT 
            rooms.id, 
            rooms.room_number, 
            rooms.floor
        FROM rooms
        WHERE rooms.id = room_id -- Filter for the specific room
    ) r
    ORDER BY dr.date;

    -- Drop the temporary table
    DROP TEMPORARY TABLE date_range;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_size` varchar(50) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT 1,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `title`, `description`, `image`, `image_size`, `banner_image`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Our Resort has been <br>present for over 20 years.', '<div class=\"text\" style=\"line-height: 30px;\">We deliver unforgettable experiences for all our visitors.</div>\r\n\r\n<div class=\"text-two\" style=\"margin-bottom: 10px;\">At Wonder Park, our goal is to create joyful memories by blending nature, adventure, and comfort into every visit. Whether you&#39;re here for a family outing, corporate event, or a relaxing retreat, we aim to exceed your expectations at every step.</div>\r\n\r\n<div class=\"text-three\">Our dedicated team works tirelessly to ensure each guest enjoys a safe, clean, and exciting environment filled with unique attractions and warm hospitality.</div>', 'images/about_us/13-07-2025-15-45-07-about_us first image.jpg', '455px * 669px', NULL, 1, 1, '2023-03-01 06:27:52', '2025-08-11 10:12:52'),
(7, 'Why Choose Us', '<p>\r\nOur objective at Wonder Park is to bring together families, friends, and nature lovers in one vibrant destination — where joy, relaxation, and adventure meet. We are passionate about creating unforgettable moments through exciting attractions, warm hospitality, and a peaceful environment for everyone to enjoy.\r\n</p>', 'images/about_us/13-07-2025-15-59-40-about_us-second-image.jpg', '614px * 350px', NULL, 1, 1, '2025-07-08 12:11:51', '2025-07-15 06:05:38'),
(8, 'The Story of Behind <br> Our Resort', '<p>Our objective at Wonder Park is to connect with the hearts and spirits of our visitors by offering a perfect blend of nature, entertainment, and relaxation. We strive to create a joyful and memorable experience where families, friends, and communities come together to celebrate life in a vibrant, welcoming environment.</p>', 'images/about_us/15-07-2025-15-51-17-image-80.jpg', '841px * 505px', NULL, 1, 1, '2025-07-08 12:29:01', '2025-07-15 09:51:17'),
(9, 'The Story of Behind <br> Our Resort', '<p>At Wonder Park, our mission is to unite the joy and spirit of our guests with the serene beauty and excitement of our surroundings. From thrilling rides to peaceful nature escapes, we offer the perfect blend of adventure and relaxation &mdash; creating lasting memories for families, friends, and all who visit.</p>', 'images/about_us/15-07-2025-15-43-19-image-81.jpg', '485px * 539px', NULL, 1, 1, '2025-07-08 12:30:17', '2025-07-15 09:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{\"attributes\":{\"id\":1,\"first_name\":\"Hipolito\",\"last_name\":\"Aufderhar\",\"email\":\"demo@demo.com\",\"email_verified_at\":\"2023-01-30T11:05:03.000000Z\",\"password\":\"$2y$10$IjQ\\/OJA1sHLz1szIOR8OEu6q4.LcpSAOtoTmQP50U8NSQm\\/n.Hr8.\",\"api_token\":\"$2y$10$va5qgqH3w32oIcZhjZ0j2e6UKQN8X7mm3qr93iDW0aYBMaTZdtCTC\",\"remember_token\":null,\"created_at\":\"2023-01-30T11:05:03.000000Z\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-30 05:05:03', '2023-01-30 05:05:03'),
(2, 'default', 'created', 'App\\Models\\UserInfo', 'created', 1, NULL, NULL, '{\"attributes\":{\"id\":1,\"user_id\":1,\"avatar\":null,\"company\":\"Bradtke, Schaden and Greenfelder\",\"phone\":\"(443) 823-0276\",\"website\":\"http:\\/\\/www.stracke.org\\/velit-voluptatem-modi-sit-vel-tenetur\",\"country\":\"CK\",\"language\":\"ik\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:03.000000Z\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-30 05:05:03', '2023-01-30 05:05:03'),
(3, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{\"attributes\":{\"id\":2,\"first_name\":\"Easter\",\"last_name\":\"Rath\",\"email\":\"admin@demo.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$1MsuFFDinnOAsROoLuGULukkYyZecQVwndX8I132oq7Ex6PweRotS\",\"api_token\":\"$2y$10$xK9PVEBOAA0wFbQtYv6KougqQQ67xXqYinrAUcwVBJifQud7pGHUa\",\"remember_token\":null,\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(4, 'default', 'created', 'App\\Models\\UserInfo', 'created', 2, NULL, NULL, '{\"attributes\":{\"id\":2,\"user_id\":2,\"avatar\":null,\"company\":\"Herman PLC\",\"phone\":\"404-488-8064\",\"website\":\"http:\\/\\/prohaska.com\\/omnis-tempore-eveniet-possimus-explicabo-totam-qui-dolorum\",\"country\":\"AD\",\"language\":\"cy\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(5, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{\"attributes\":{\"id\":3,\"first_name\":\"Ethan\",\"last_name\":\"Okuneva\",\"email\":\"pmraz@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"mrkayf7HuG\",\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(6, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{\"attributes\":{\"id\":4,\"first_name\":\"Kaley\",\"last_name\":\"Friesen\",\"email\":\"quincy08@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"TtHHY3OgKO\",\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(7, 'default', 'created', 'App\\Models\\User', 'created', 5, NULL, NULL, '{\"attributes\":{\"id\":5,\"first_name\":\"Garret\",\"last_name\":\"Fisher\",\"email\":\"fflatley@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"s3fUCKxKnm\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(8, 'default', 'created', 'App\\Models\\User', 'created', 6, NULL, NULL, '{\"attributes\":{\"id\":6,\"first_name\":\"Hellen\",\"last_name\":\"Smith\",\"email\":\"kellie.bednar@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"LonPVwhUAo\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(9, 'default', 'created', 'App\\Models\\User', 'created', 7, NULL, NULL, '{\"attributes\":{\"id\":7,\"first_name\":\"Malinda\",\"last_name\":\"Hahn\",\"email\":\"yazmin23@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"XjogJZvnUg\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(10, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{\"attributes\":{\"id\":8,\"first_name\":\"Kim\",\"last_name\":\"Quigley\",\"email\":\"ndubuque@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"HwSwupDWEf\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(11, 'default', 'created', 'App\\Models\\User', 'created', 9, NULL, NULL, '{\"attributes\":{\"id\":9,\"first_name\":\"Bernadette\",\"last_name\":\"Ritchie\",\"email\":\"monserrat.schinner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"mmJmSKhUDq\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(12, 'default', 'created', 'App\\Models\\User', 'created', 10, NULL, NULL, '{\"attributes\":{\"id\":10,\"first_name\":\"Rory\",\"last_name\":\"Zulauf\",\"email\":\"rice.tate@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"fiqjbkDGQV\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(13, 'default', 'created', 'App\\Models\\User', 'created', 11, NULL, NULL, '{\"attributes\":{\"id\":11,\"first_name\":\"Gussie\",\"last_name\":\"Hayes\",\"email\":\"senger.timmy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UtaKOXLr7g\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(14, 'default', 'created', 'App\\Models\\User', 'created', 12, NULL, NULL, '{\"attributes\":{\"id\":12,\"first_name\":\"Trystan\",\"last_name\":\"Watsica\",\"email\":\"stacy78@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"3D0OK4iSJd\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(15, 'default', 'created', 'App\\Models\\User', 'created', 13, NULL, NULL, '{\"attributes\":{\"id\":13,\"first_name\":\"Zola\",\"last_name\":\"Schumm\",\"email\":\"lily.labadie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"7gbUeuiIEH\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(16, 'default', 'created', 'App\\Models\\User', 'created', 14, NULL, NULL, '{\"attributes\":{\"id\":14,\"first_name\":\"Savannah\",\"last_name\":\"Turner\",\"email\":\"kuhic.jayce@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"vab2Dhe5Va\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(17, 'default', 'created', 'App\\Models\\User', 'created', 15, NULL, NULL, '{\"attributes\":{\"id\":15,\"first_name\":\"Judge\",\"last_name\":\"Crona\",\"email\":\"geoffrey41@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"2vNdlvs79Y\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(18, 'default', 'created', 'App\\Models\\User', 'created', 16, NULL, NULL, '{\"attributes\":{\"id\":16,\"first_name\":\"Angie\",\"last_name\":\"Howell\",\"email\":\"kenny59@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"g5bwoW8BuS\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(19, 'default', 'created', 'App\\Models\\User', 'created', 17, NULL, NULL, '{\"attributes\":{\"id\":17,\"first_name\":\"Elwin\",\"last_name\":\"Hammes\",\"email\":\"alfredo59@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"VhKET9W8EN\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(20, 'default', 'created', 'App\\Models\\User', 'created', 18, NULL, NULL, '{\"attributes\":{\"id\":18,\"first_name\":\"Hailie\",\"last_name\":\"Conn\",\"email\":\"jace.quigley@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xXaP8P73y3\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(21, 'default', 'created', 'App\\Models\\User', 'created', 19, NULL, NULL, '{\"attributes\":{\"id\":19,\"first_name\":\"Juwan\",\"last_name\":\"Grimes\",\"email\":\"kohler.norris@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Wfq3z3tmye\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(22, 'default', 'created', 'App\\Models\\User', 'created', 20, NULL, NULL, '{\"attributes\":{\"id\":20,\"first_name\":\"Antonetta\",\"last_name\":\"Kirlin\",\"email\":\"harris.rosamond@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"P10us5pot6\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(23, 'default', 'created', 'App\\Models\\User', 'created', 21, NULL, NULL, '{\"attributes\":{\"id\":21,\"first_name\":\"Green\",\"last_name\":\"Rath\",\"email\":\"umckenzie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"gwaUUEQHIv\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(24, 'default', 'created', 'App\\Models\\User', 'created', 22, NULL, NULL, '{\"attributes\":{\"id\":22,\"first_name\":\"Vincenza\",\"last_name\":\"Von\",\"email\":\"violet.larson@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"tKkC19a3zp\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(25, 'default', 'created', 'App\\Models\\User', 'created', 23, NULL, NULL, '{\"attributes\":{\"id\":23,\"first_name\":\"Ladarius\",\"last_name\":\"Schaden\",\"email\":\"ilittel@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"MezWxI1rzd\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(26, 'default', 'created', 'App\\Models\\User', 'created', 24, NULL, NULL, '{\"attributes\":{\"id\":24,\"first_name\":\"Henriette\",\"last_name\":\"Lowe\",\"email\":\"tcollier@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qsC9Tp5NrM\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(27, 'default', 'created', 'App\\Models\\User', 'created', 25, NULL, NULL, '{\"attributes\":{\"id\":25,\"first_name\":\"Carlee\",\"last_name\":\"Turcotte\",\"email\":\"bogisich.pete@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"SczBhHe0nJ\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(28, 'default', 'created', 'App\\Models\\User', 'created', 26, NULL, NULL, '{\"attributes\":{\"id\":26,\"first_name\":\"Felicia\",\"last_name\":\"Sporer\",\"email\":\"eldora13@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rot02Hiukz\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(29, 'default', 'created', 'App\\Models\\User', 'created', 27, NULL, NULL, '{\"attributes\":{\"id\":27,\"first_name\":\"Alanna\",\"last_name\":\"Schaefer\",\"email\":\"derrick.beier@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"yup6EMcuEf\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(30, 'default', 'created', 'App\\Models\\User', 'created', 28, NULL, NULL, '{\"attributes\":{\"id\":28,\"first_name\":\"Christy\",\"last_name\":\"Runolfsson\",\"email\":\"emanuel.walsh@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"IcgX9O2NVn\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(31, 'default', 'created', 'App\\Models\\User', 'created', 29, NULL, NULL, '{\"attributes\":{\"id\":29,\"first_name\":\"Cecile\",\"last_name\":\"Becker\",\"email\":\"lemke.jadyn@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nTmZ4goJEv\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(32, 'default', 'created', 'App\\Models\\User', 'created', 30, NULL, NULL, '{\"attributes\":{\"id\":30,\"first_name\":\"Evan\",\"last_name\":\"Hudson\",\"email\":\"tthompson@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"SE77STcTKC\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(33, 'default', 'created', 'App\\Models\\User', 'created', 31, NULL, NULL, '{\"attributes\":{\"id\":31,\"first_name\":\"Demarco\",\"last_name\":\"Rath\",\"email\":\"jpfannerstill@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"8NgIfyBQES\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(34, 'default', 'created', 'App\\Models\\User', 'created', 32, NULL, NULL, '{\"attributes\":{\"id\":32,\"first_name\":\"Napoleon\",\"last_name\":\"Pollich\",\"email\":\"vallie.dubuque@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"EZoVRtkUCp\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(35, 'default', 'created', 'App\\Models\\User', 'created', 33, NULL, NULL, '{\"attributes\":{\"id\":33,\"first_name\":\"Hilbert\",\"last_name\":\"Lynch\",\"email\":\"golda93@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"RXAvhRqi4X\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(36, 'default', 'created', 'App\\Models\\User', 'created', 34, NULL, NULL, '{\"attributes\":{\"id\":34,\"first_name\":\"Melany\",\"last_name\":\"Kuvalis\",\"email\":\"krystel25@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qpUFG2DM4p\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(37, 'default', 'created', 'App\\Models\\User', 'created', 35, NULL, NULL, '{\"attributes\":{\"id\":35,\"first_name\":\"Marlee\",\"last_name\":\"Cormier\",\"email\":\"nadia.heller@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"PFEyDUjXgV\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(38, 'default', 'created', 'App\\Models\\User', 'created', 36, NULL, NULL, '{\"attributes\":{\"id\":36,\"first_name\":\"Jayden\",\"last_name\":\"Schoen\",\"email\":\"america.purdy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9poiXp5qbD\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(39, 'default', 'created', 'App\\Models\\User', 'created', 37, NULL, NULL, '{\"attributes\":{\"id\":37,\"first_name\":\"Alene\",\"last_name\":\"Mueller\",\"email\":\"pshields@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"k11ebpE9oG\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(40, 'default', 'created', 'App\\Models\\User', 'created', 38, NULL, NULL, '{\"attributes\":{\"id\":38,\"first_name\":\"Obie\",\"last_name\":\"Crist\",\"email\":\"dare.queenie@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ysGN2Yg4FW\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(41, 'default', 'created', 'App\\Models\\User', 'created', 39, NULL, NULL, '{\"attributes\":{\"id\":39,\"first_name\":\"Flossie\",\"last_name\":\"Collins\",\"email\":\"huel.grant@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xV3VQ6HkgJ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(42, 'default', 'created', 'App\\Models\\User', 'created', 40, NULL, NULL, '{\"attributes\":{\"id\":40,\"first_name\":\"Chasity\",\"last_name\":\"Ondricka\",\"email\":\"courtney65@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"lYYqHfhOAN\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(43, 'default', 'created', 'App\\Models\\User', 'created', 41, NULL, NULL, '{\"attributes\":{\"id\":41,\"first_name\":\"Gerry\",\"last_name\":\"Cummings\",\"email\":\"jarod.stanton@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"fNB77b2MoO\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(44, 'default', 'created', 'App\\Models\\User', 'created', 42, NULL, NULL, '{\"attributes\":{\"id\":42,\"first_name\":\"Ian\",\"last_name\":\"Walker\",\"email\":\"reichel.ford@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"zq1TGXCRdt\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(45, 'default', 'created', 'App\\Models\\User', 'created', 43, NULL, NULL, '{\"attributes\":{\"id\":43,\"first_name\":\"Moses\",\"last_name\":\"Ritchie\",\"email\":\"wunsch.lynn@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"t9zkKOM4WJ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(46, 'default', 'created', 'App\\Models\\User', 'created', 44, NULL, NULL, '{\"attributes\":{\"id\":44,\"first_name\":\"Fern\",\"last_name\":\"McDermott\",\"email\":\"murazik.rosemary@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"KrbuSUD1XQ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(47, 'default', 'created', 'App\\Models\\User', 'created', 45, NULL, NULL, '{\"attributes\":{\"id\":45,\"first_name\":\"Amani\",\"last_name\":\"West\",\"email\":\"hbernhard@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"1xL6mQN7FO\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(48, 'default', 'created', 'App\\Models\\User', 'created', 46, NULL, NULL, '{\"attributes\":{\"id\":46,\"first_name\":\"Leta\",\"last_name\":\"O\'Reilly\",\"email\":\"leora59@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"TU5fpNB9Nb\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(49, 'default', 'created', 'App\\Models\\User', 'created', 47, NULL, NULL, '{\"attributes\":{\"id\":47,\"first_name\":\"Hilda\",\"last_name\":\"Erdman\",\"email\":\"oswaldo.mann@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xXNp2FmCOo\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(50, 'default', 'created', 'App\\Models\\User', 'created', 48, NULL, NULL, '{\"attributes\":{\"id\":48,\"first_name\":\"Deborah\",\"last_name\":\"Considine\",\"email\":\"auer.stephanie@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"alDAhYCNuu\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(51, 'default', 'created', 'App\\Models\\User', 'created', 49, NULL, NULL, '{\"attributes\":{\"id\":49,\"first_name\":\"Garnett\",\"last_name\":\"Wiegand\",\"email\":\"bryce.denesik@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"tNNJfEkqMK\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(52, 'default', 'created', 'App\\Models\\User', 'created', 50, NULL, NULL, '{\"attributes\":{\"id\":50,\"first_name\":\"Roscoe\",\"last_name\":\"Feeney\",\"email\":\"wpadberg@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"kpe5IiXSVa\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(53, 'default', 'created', 'App\\Models\\User', 'created', 51, NULL, NULL, '{\"attributes\":{\"id\":51,\"first_name\":\"Naomie\",\"last_name\":\"Watsica\",\"email\":\"tleuschke@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"sWw2mC4a2S\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(54, 'default', 'created', 'App\\Models\\User', 'created', 52, NULL, NULL, '{\"attributes\":{\"id\":52,\"first_name\":\"Mercedes\",\"last_name\":\"Dickinson\",\"email\":\"cory.upton@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qeOebkITj7\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(55, 'default', 'created', 'App\\Models\\User', 'created', 53, NULL, NULL, '{\"attributes\":{\"id\":53,\"first_name\":\"Kieran\",\"last_name\":\"Kemmer\",\"email\":\"andy.cummerata@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"I3drbkDH4I\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(56, 'default', 'created', 'App\\Models\\User', 'created', 54, NULL, NULL, '{\"attributes\":{\"id\":54,\"first_name\":\"Rhea\",\"last_name\":\"Dickinson\",\"email\":\"swaniawski.griffin@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"WH6pX1HwR6\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(57, 'default', 'created', 'App\\Models\\User', 'created', 55, NULL, NULL, '{\"attributes\":{\"id\":55,\"first_name\":\"Hazel\",\"last_name\":\"Cronin\",\"email\":\"rutherford.kacie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9ULw9zXY2I\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(58, 'default', 'created', 'App\\Models\\User', 'created', 56, NULL, NULL, '{\"attributes\":{\"id\":56,\"first_name\":\"Maiya\",\"last_name\":\"Heidenreich\",\"email\":\"thaddeus59@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"krgAteBVIN\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(59, 'default', 'created', 'App\\Models\\User', 'created', 57, NULL, NULL, '{\"attributes\":{\"id\":57,\"first_name\":\"Winnifred\",\"last_name\":\"Bartoletti\",\"email\":\"garry05@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"aIxomeVwSJ\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(60, 'default', 'created', 'App\\Models\\User', 'created', 58, NULL, NULL, '{\"attributes\":{\"id\":58,\"first_name\":\"Sarina\",\"last_name\":\"Herman\",\"email\":\"layne.jacobi@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"3TuPDrdjpi\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(61, 'default', 'created', 'App\\Models\\User', 'created', 59, NULL, NULL, '{\"attributes\":{\"id\":59,\"first_name\":\"Hallie\",\"last_name\":\"Jerde\",\"email\":\"hprohaska@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"S4lM8Z5lhU\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(62, 'default', 'created', 'App\\Models\\User', 'created', 60, NULL, NULL, '{\"attributes\":{\"id\":60,\"first_name\":\"Kailyn\",\"last_name\":\"West\",\"email\":\"mhammes@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nv9ONrYWsc\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(63, 'default', 'created', 'App\\Models\\User', 'created', 61, NULL, NULL, '{\"attributes\":{\"id\":61,\"first_name\":\"Morris\",\"last_name\":\"Denesik\",\"email\":\"junius01@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xVm5v9SPeb\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(64, 'default', 'created', 'App\\Models\\User', 'created', 62, NULL, NULL, '{\"attributes\":{\"id\":62,\"first_name\":\"Cielo\",\"last_name\":\"Smitham\",\"email\":\"derek.mohr@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"OmitnGORr4\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(65, 'default', 'created', 'App\\Models\\User', 'created', 63, NULL, NULL, '{\"attributes\":{\"id\":63,\"first_name\":\"Shirley\",\"last_name\":\"Nader\",\"email\":\"serenity35@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"KEeYzIfshy\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(66, 'default', 'created', 'App\\Models\\User', 'created', 64, NULL, NULL, '{\"attributes\":{\"id\":64,\"first_name\":\"Beverly\",\"last_name\":\"Beatty\",\"email\":\"marguerite.schamberger@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"8gJidekKDF\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(67, 'default', 'created', 'App\\Models\\User', 'created', 65, NULL, NULL, '{\"attributes\":{\"id\":65,\"first_name\":\"Elvera\",\"last_name\":\"Ledner\",\"email\":\"reginald.maggio@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"vxAPW2mHuf\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(68, 'default', 'created', 'App\\Models\\User', 'created', 66, NULL, NULL, '{\"attributes\":{\"id\":66,\"first_name\":\"Shanelle\",\"last_name\":\"Terry\",\"email\":\"elody.oberbrunner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"dd6SGqwrRn\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(69, 'default', 'created', 'App\\Models\\User', 'created', 67, NULL, NULL, '{\"attributes\":{\"id\":67,\"first_name\":\"Pascale\",\"last_name\":\"Wilkinson\",\"email\":\"ford44@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rRypnV43F5\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(70, 'default', 'created', 'App\\Models\\User', 'created', 68, NULL, NULL, '{\"attributes\":{\"id\":68,\"first_name\":\"Destiny\",\"last_name\":\"Stamm\",\"email\":\"evie60@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ShsuLLom2q\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(71, 'default', 'created', 'App\\Models\\User', 'created', 69, NULL, NULL, '{\"attributes\":{\"id\":69,\"first_name\":\"Marilie\",\"last_name\":\"Skiles\",\"email\":\"dibbert.karina@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"GWaQ3tWxfL\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(72, 'default', 'created', 'App\\Models\\User', 'created', 70, NULL, NULL, '{\"attributes\":{\"id\":70,\"first_name\":\"Theodora\",\"last_name\":\"Beatty\",\"email\":\"kreiger.elissa@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"O3j9EZJlsC\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(73, 'default', 'created', 'App\\Models\\User', 'created', 71, NULL, NULL, '{\"attributes\":{\"id\":71,\"first_name\":\"Isabel\",\"last_name\":\"Gibson\",\"email\":\"ogleichner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Rm2bgN7hV7\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(74, 'default', 'created', 'App\\Models\\User', 'created', 72, NULL, NULL, '{\"attributes\":{\"id\":72,\"first_name\":\"Mallory\",\"last_name\":\"Heller\",\"email\":\"larkin.stone@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"7k4RknawAm\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(75, 'default', 'created', 'App\\Models\\User', 'created', 73, NULL, NULL, '{\"attributes\":{\"id\":73,\"first_name\":\"Sigmund\",\"last_name\":\"Ortiz\",\"email\":\"roslyn74@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"GCaqhXJbmV\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(76, 'default', 'created', 'App\\Models\\User', 'created', 74, NULL, NULL, '{\"attributes\":{\"id\":74,\"first_name\":\"Genoveva\",\"last_name\":\"Towne\",\"email\":\"alyson35@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"zCb74E1GPV\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(77, 'default', 'created', 'App\\Models\\User', 'created', 75, NULL, NULL, '{\"attributes\":{\"id\":75,\"first_name\":\"Astrid\",\"last_name\":\"Metz\",\"email\":\"theodora.schaefer@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"RPhowGhT6M\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(78, 'default', 'created', 'App\\Models\\User', 'created', 76, NULL, NULL, '{\"attributes\":{\"id\":76,\"first_name\":\"Myah\",\"last_name\":\"Steuber\",\"email\":\"carole89@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"d4CW2Qw5Iw\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(79, 'default', 'created', 'App\\Models\\User', 'created', 77, NULL, NULL, '{\"attributes\":{\"id\":77,\"first_name\":\"Verla\",\"last_name\":\"Gerhold\",\"email\":\"bogisich.nona@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"B5FdbRvX2n\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(80, 'default', 'created', 'App\\Models\\User', 'created', 78, NULL, NULL, '{\"attributes\":{\"id\":78,\"first_name\":\"Leola\",\"last_name\":\"Toy\",\"email\":\"drew63@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ENgDrPqWPe\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(81, 'default', 'created', 'App\\Models\\User', 'created', 79, NULL, NULL, '{\"attributes\":{\"id\":79,\"first_name\":\"Myrtle\",\"last_name\":\"Haley\",\"email\":\"ressie44@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"gJed20DG3Z\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(82, 'default', 'created', 'App\\Models\\User', 'created', 80, NULL, NULL, '{\"attributes\":{\"id\":80,\"first_name\":\"Benjamin\",\"last_name\":\"Jenkins\",\"email\":\"caitlyn.harvey@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rmAN2rO4ym\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(83, 'default', 'created', 'App\\Models\\User', 'created', 81, NULL, NULL, '{\"attributes\":{\"id\":81,\"first_name\":\"Wayne\",\"last_name\":\"Wilderman\",\"email\":\"schuppe.esperanza@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"uLkLMDY4Oq\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(84, 'default', 'created', 'App\\Models\\User', 'created', 82, NULL, NULL, '{\"attributes\":{\"id\":82,\"first_name\":\"Jules\",\"last_name\":\"Keebler\",\"email\":\"mellie18@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"AxZeuFKmfY\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(85, 'default', 'created', 'App\\Models\\User', 'created', 83, NULL, NULL, '{\"attributes\":{\"id\":83,\"first_name\":\"Zaria\",\"last_name\":\"Wisoky\",\"email\":\"monica90@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"n8FYas3Wzp\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(86, 'default', 'created', 'App\\Models\\User', 'created', 84, NULL, NULL, '{\"attributes\":{\"id\":84,\"first_name\":\"Coy\",\"last_name\":\"Mante\",\"email\":\"reynolds.ciara@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Hq7gz56jWg\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(87, 'default', 'created', 'App\\Models\\User', 'created', 85, NULL, NULL, '{\"attributes\":{\"id\":85,\"first_name\":\"Darlene\",\"last_name\":\"Breitenberg\",\"email\":\"gudrun.morissette@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ofJqanD3yo\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(88, 'default', 'created', 'App\\Models\\User', 'created', 86, NULL, NULL, '{\"attributes\":{\"id\":86,\"first_name\":\"Lane\",\"last_name\":\"Sawayn\",\"email\":\"zcasper@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Qll9tF02Ye\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(89, 'default', 'created', 'App\\Models\\User', 'created', 87, NULL, NULL, '{\"attributes\":{\"id\":87,\"first_name\":\"Lucile\",\"last_name\":\"Osinski\",\"email\":\"cooper15@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UiuaKq4Mtl\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(90, 'default', 'created', 'App\\Models\\User', 'created', 88, NULL, NULL, '{\"attributes\":{\"id\":88,\"first_name\":\"Claire\",\"last_name\":\"Dooley\",\"email\":\"tianna70@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nzzmvrqAiH\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(91, 'default', 'created', 'App\\Models\\User', 'created', 89, NULL, NULL, '{\"attributes\":{\"id\":89,\"first_name\":\"Yvette\",\"last_name\":\"Toy\",\"email\":\"marianna.jerde@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"wof9Yp8Wro\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(92, 'default', 'created', 'App\\Models\\User', 'created', 90, NULL, NULL, '{\"attributes\":{\"id\":90,\"first_name\":\"Evalyn\",\"last_name\":\"Abshire\",\"email\":\"chermiston@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"HTV2gdhxMm\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(93, 'default', 'created', 'App\\Models\\User', 'created', 91, NULL, NULL, '{\"attributes\":{\"id\":91,\"first_name\":\"Dasia\",\"last_name\":\"Bergnaum\",\"email\":\"xpaucek@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9Opm7FMNdg\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(94, 'default', 'created', 'App\\Models\\User', 'created', 92, NULL, NULL, '{\"attributes\":{\"id\":92,\"first_name\":\"Evangeline\",\"last_name\":\"Conroy\",\"email\":\"makenna53@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qG7WhhST3M\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(95, 'default', 'created', 'App\\Models\\User', 'created', 93, NULL, NULL, '{\"attributes\":{\"id\":93,\"first_name\":\"Gina\",\"last_name\":\"Hilpert\",\"email\":\"piper55@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"bsVFGZV5zT\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(96, 'default', 'created', 'App\\Models\\User', 'created', 94, NULL, NULL, '{\"attributes\":{\"id\":94,\"first_name\":\"Cristina\",\"last_name\":\"Koss\",\"email\":\"jgrady@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"QOjqDacaIg\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(97, 'default', 'created', 'App\\Models\\User', 'created', 95, NULL, NULL, '{\"attributes\":{\"id\":95,\"first_name\":\"Alfonso\",\"last_name\":\"Smith\",\"email\":\"areichert@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UUkcj5VYS5\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(98, 'default', 'created', 'App\\Models\\User', 'created', 96, NULL, NULL, '{\"attributes\":{\"id\":96,\"first_name\":\"Hilda\",\"last_name\":\"Crist\",\"email\":\"salma61@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"QKbmp8d3F5\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(99, 'default', 'created', 'App\\Models\\User', 'created', 97, NULL, NULL, '{\"attributes\":{\"id\":97,\"first_name\":\"Cindy\",\"last_name\":\"Bauch\",\"email\":\"schaefer.fernando@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Ip4gTYFvXf\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(100, 'default', 'created', 'App\\Models\\User', 'created', 98, NULL, NULL, '{\"attributes\":{\"id\":98,\"first_name\":\"Van\",\"last_name\":\"Bogan\",\"email\":\"jacobson.adolfo@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"yjJUSXrakq\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(101, 'default', 'created', 'App\\Models\\User', 'created', 99, NULL, NULL, '{\"attributes\":{\"id\":99,\"first_name\":\"Magnolia\",\"last_name\":\"Donnelly\",\"email\":\"vidal.hodkiewicz@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"eeatBeIL6b\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(102, 'default', 'created', 'App\\Models\\User', 'created', 100, NULL, NULL, '{\"attributes\":{\"id\":100,\"first_name\":\"Mozelle\",\"last_name\":\"Kutch\",\"email\":\"rconroy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"4aGgDbTzBK\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(103, 'default', 'created', 'App\\Models\\User', 'created', 101, NULL, NULL, '{\"attributes\":{\"id\":101,\"first_name\":\"Ashlynn\",\"last_name\":\"Treutel\",\"email\":\"schinner.thaddeus@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"IdQEP9xmzy\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(104, 'default', 'created', 'App\\Models\\User', 'created', 102, NULL, NULL, '{\"attributes\":{\"id\":102,\"first_name\":\"Mozell\",\"last_name\":\"Ruecker\",\"email\":\"zschmidt@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"h6zfzsNxNy\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(105, 'default', 'created', 'App\\Models\\UserInfo', 'created', 3, NULL, NULL, '{\"attributes\":{\"id\":3,\"user_id\":3,\"avatar\":null,\"company\":\"Halvorson Group\",\"phone\":\"+1 (430) 280-0462\",\"website\":\"http:\\/\\/www.zieme.info\\/veritatis-id-molestiae-ut-atque.html\",\"country\":\"BA\",\"language\":\"be\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(106, 'default', 'created', 'App\\Models\\UserInfo', 'created', 4, NULL, NULL, '{\"attributes\":{\"id\":4,\"user_id\":4,\"avatar\":null,\"company\":\"Von LLC\",\"phone\":\"845.285.4860\",\"website\":\"https:\\/\\/www.zboncak.com\\/reprehenderit-et-et-dolore-adipisci\",\"country\":\"IT\",\"language\":\"el\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(107, 'default', 'created', 'App\\Models\\UserInfo', 'created', 5, NULL, NULL, '{\"attributes\":{\"id\":5,\"user_id\":5,\"avatar\":null,\"company\":\"Upton-Stamm\",\"phone\":\"1-561-410-5112\",\"website\":\"http:\\/\\/www.marquardt.info\\/voluptatem-consequatur-repellat-aperiam-ipsam\",\"country\":\"RE\",\"language\":\"ho\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(108, 'default', 'created', 'App\\Models\\UserInfo', 'created', 6, NULL, NULL, '{\"attributes\":{\"id\":6,\"user_id\":6,\"avatar\":null,\"company\":\"Flatley, Howell and Lubowitz\",\"phone\":\"1-865-480-6435\",\"website\":\"https:\\/\\/deckow.com\\/aliquam-in-ut-autem-esse-voluptas-facilis.html\",\"country\":\"HT\",\"language\":\"ps\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(109, 'default', 'created', 'App\\Models\\UserInfo', 'created', 7, NULL, NULL, '{\"attributes\":{\"id\":7,\"user_id\":7,\"avatar\":null,\"company\":\"Watsica, Gutkowski and Blanda\",\"phone\":\"+1-445-910-2778\",\"website\":\"http:\\/\\/marquardt.com\\/\",\"country\":\"CF\",\"language\":\"ab\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(110, 'default', 'created', 'App\\Models\\UserInfo', 'created', 8, NULL, NULL, '{\"attributes\":{\"id\":8,\"user_id\":8,\"avatar\":null,\"company\":\"Deckow Ltd\",\"phone\":\"434.253.5867\",\"website\":\"http:\\/\\/www.balistreri.com\\/rem-exercitationem-illo-facilis-sunt.html\",\"country\":\"BN\",\"language\":\"iu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(111, 'default', 'created', 'App\\Models\\UserInfo', 'created', 9, NULL, NULL, '{\"attributes\":{\"id\":9,\"user_id\":9,\"avatar\":null,\"company\":\"Fisher, Mertz and Collins\",\"phone\":\"1-863-860-2159\",\"website\":\"https:\\/\\/www.hayes.com\\/placeat-quia-molestias-et-et-optio-minima-mollitia-excepturi\",\"country\":\"TT\",\"language\":\"ig\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(112, 'default', 'created', 'App\\Models\\UserInfo', 'created', 10, NULL, NULL, '{\"attributes\":{\"id\":10,\"user_id\":10,\"avatar\":null,\"company\":\"Bartoletti Group\",\"phone\":\"+1-248-350-9269\",\"website\":\"http:\\/\\/gottlieb.com\\/non-accusamus-non-aut-dolores-aliquid-incidunt\",\"country\":\"CI\",\"language\":\"li\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(113, 'default', 'created', 'App\\Models\\UserInfo', 'created', 11, NULL, NULL, '{\"attributes\":{\"id\":11,\"user_id\":11,\"avatar\":null,\"company\":\"Dickens-Parker\",\"phone\":\"+1.281.354.0408\",\"website\":\"http:\\/\\/kris.com\\/\",\"country\":\"BB\",\"language\":\"mg\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(114, 'default', 'created', 'App\\Models\\UserInfo', 'created', 12, NULL, NULL, '{\"attributes\":{\"id\":12,\"user_id\":12,\"avatar\":null,\"company\":\"Homenick Inc\",\"phone\":\"+1 (320) 544-0921\",\"website\":\"http:\\/\\/www.lang.com\\/omnis-temporibus-dignissimos-delectus-delectus-ipsam-omnis-iusto-quos\",\"country\":\"HR\",\"language\":\"tr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(115, 'default', 'created', 'App\\Models\\UserInfo', 'created', 13, NULL, NULL, '{\"attributes\":{\"id\":13,\"user_id\":13,\"avatar\":null,\"company\":\"Lemke LLC\",\"phone\":\"980-678-7084\",\"website\":\"http:\\/\\/www.rogahn.org\\/\",\"country\":\"KI\",\"language\":\"az\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(116, 'default', 'created', 'App\\Models\\UserInfo', 'created', 14, NULL, NULL, '{\"attributes\":{\"id\":14,\"user_id\":14,\"avatar\":null,\"company\":\"Braun Ltd\",\"phone\":\"1-629-228-6993\",\"website\":\"http:\\/\\/donnelly.com\\/\",\"country\":\"TZ\",\"language\":\"tt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(117, 'default', 'created', 'App\\Models\\UserInfo', 'created', 15, NULL, NULL, '{\"attributes\":{\"id\":15,\"user_id\":15,\"avatar\":null,\"company\":\"Reichel PLC\",\"phone\":\"1-769-782-1600\",\"website\":\"http:\\/\\/purdy.com\\/ullam-dolor-magni-tempora-eos\",\"country\":\"JP\",\"language\":\"sa\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(118, 'default', 'created', 'App\\Models\\UserInfo', 'created', 16, NULL, NULL, '{\"attributes\":{\"id\":16,\"user_id\":16,\"avatar\":null,\"company\":\"Windler and Sons\",\"phone\":\"239-860-4177\",\"website\":\"https:\\/\\/schumm.net\\/odit-quo-omnis-qui-beatae-saepe-voluptate.html\",\"country\":\"TH\",\"language\":\"na\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(119, 'default', 'created', 'App\\Models\\UserInfo', 'created', 17, NULL, NULL, '{\"attributes\":{\"id\":17,\"user_id\":17,\"avatar\":null,\"company\":\"Lowe, Vandervort and Feest\",\"phone\":\"707.763.6241\",\"website\":\"https:\\/\\/www.dach.biz\\/quisquam-placeat-expedita-quia\",\"country\":\"OM\",\"language\":\"nn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(120, 'default', 'created', 'App\\Models\\UserInfo', 'created', 18, NULL, NULL, '{\"attributes\":{\"id\":18,\"user_id\":18,\"avatar\":null,\"company\":\"Glover-Schneider\",\"phone\":\"1-979-819-8495\",\"website\":\"http:\\/\\/dare.org\\/\",\"country\":\"LC\",\"language\":\"an\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(121, 'default', 'created', 'App\\Models\\UserInfo', 'created', 19, NULL, NULL, '{\"attributes\":{\"id\":19,\"user_id\":19,\"avatar\":null,\"company\":\"Mohr-Gulgowski\",\"phone\":\"850.235.1441\",\"website\":\"https:\\/\\/cassin.biz\\/atque-rerum-soluta-facere.html\",\"country\":\"IL\",\"language\":\"sa\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(122, 'default', 'created', 'App\\Models\\UserInfo', 'created', 20, NULL, NULL, '{\"attributes\":{\"id\":20,\"user_id\":20,\"avatar\":null,\"company\":\"Schaefer, Turcotte and Quitzon\",\"phone\":\"208-900-5173\",\"website\":\"http:\\/\\/www.herman.info\\/\",\"country\":\"BI\",\"language\":\"fo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(123, 'default', 'created', 'App\\Models\\UserInfo', 'created', 21, NULL, NULL, '{\"attributes\":{\"id\":21,\"user_id\":21,\"avatar\":null,\"company\":\"Spinka-Hahn\",\"phone\":\"+1-512-875-0637\",\"website\":\"https:\\/\\/johns.org\\/perspiciatis-doloribus-ut-voluptas-omnis.html\",\"country\":\"DM\",\"language\":\"co\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(124, 'default', 'created', 'App\\Models\\UserInfo', 'created', 22, NULL, NULL, '{\"attributes\":{\"id\":22,\"user_id\":22,\"avatar\":null,\"company\":\"Boehm, Raynor and Cruickshank\",\"phone\":\"+16468822256\",\"website\":\"http:\\/\\/krajcik.info\\/dolores-nam-nulla-aperiam-eius\",\"country\":\"RU\",\"language\":\"lu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(125, 'default', 'created', 'App\\Models\\UserInfo', 'created', 23, NULL, NULL, '{\"attributes\":{\"id\":23,\"user_id\":23,\"avatar\":null,\"company\":\"Morar-Champlin\",\"phone\":\"+1-864-476-3168\",\"website\":\"https:\\/\\/www.schuster.com\\/voluptatem-ducimus-facere-cum-odit\",\"country\":\"CL\",\"language\":\"ig\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(126, 'default', 'created', 'App\\Models\\UserInfo', 'created', 24, NULL, NULL, '{\"attributes\":{\"id\":24,\"user_id\":24,\"avatar\":null,\"company\":\"Torp-Altenwerth\",\"phone\":\"+1-574-241-0944\",\"website\":\"https:\\/\\/goldner.com\\/et-aut-est-eius-a-reprehenderit-quod-quia.html\",\"country\":\"UM\",\"language\":\"ch\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(127, 'default', 'created', 'App\\Models\\UserInfo', 'created', 25, NULL, NULL, '{\"attributes\":{\"id\":25,\"user_id\":25,\"avatar\":null,\"company\":\"Harber-Renner\",\"phone\":\"812.284.0837\",\"website\":\"https:\\/\\/gerhold.com\\/sunt-ab-quidem-sunt-incidunt-quidem-sunt.html\",\"country\":\"LS\",\"language\":\"pi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(128, 'default', 'created', 'App\\Models\\UserInfo', 'created', 26, NULL, NULL, '{\"attributes\":{\"id\":26,\"user_id\":26,\"avatar\":null,\"company\":\"Schinner PLC\",\"phone\":\"508.672.3563\",\"website\":\"https:\\/\\/oberbrunner.com\\/est-quaerat-nam-quia-non.html\",\"country\":\"MV\",\"language\":\"mt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(129, 'default', 'created', 'App\\Models\\UserInfo', 'created', 27, NULL, NULL, '{\"attributes\":{\"id\":27,\"user_id\":27,\"avatar\":null,\"company\":\"Dibbert PLC\",\"phone\":\"386.765.2103\",\"website\":\"https:\\/\\/www.gislason.org\\/reiciendis-in-vero-suscipit-dolore-aspernatur-fugit-et\",\"country\":\"SI\",\"language\":\"mi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(130, 'default', 'created', 'App\\Models\\UserInfo', 'created', 28, NULL, NULL, '{\"attributes\":{\"id\":28,\"user_id\":28,\"avatar\":null,\"company\":\"Watsica, Maggio and Christiansen\",\"phone\":\"(845) 831-9784\",\"website\":\"http:\\/\\/www.gibson.org\\/rerum-distinctio-vel-hic\",\"country\":\"IM\",\"language\":\"nr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(131, 'default', 'created', 'App\\Models\\UserInfo', 'created', 29, NULL, NULL, '{\"attributes\":{\"id\":29,\"user_id\":29,\"avatar\":null,\"company\":\"Boyer PLC\",\"phone\":\"458-508-8836\",\"website\":\"http:\\/\\/www.runolfsson.com\\/voluptate-temporibus-minima-quia-reiciendis\",\"country\":\"VI\",\"language\":\"si\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(132, 'default', 'created', 'App\\Models\\UserInfo', 'created', 30, NULL, NULL, '{\"attributes\":{\"id\":30,\"user_id\":30,\"avatar\":null,\"company\":\"Lehner PLC\",\"phone\":\"+1-341-990-7613\",\"website\":\"http:\\/\\/www.berge.com\\/architecto-vel-rerum-fuga-iste-sunt-aliquid.html\",\"country\":\"MV\",\"language\":\"lt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(133, 'default', 'created', 'App\\Models\\UserInfo', 'created', 31, NULL, NULL, '{\"attributes\":{\"id\":31,\"user_id\":31,\"avatar\":null,\"company\":\"Cormier and Sons\",\"phone\":\"352.465.0917\",\"website\":\"http:\\/\\/www.kuhlman.com\\/saepe-doloribus-est-possimus-aut-ex-repellat\",\"country\":\"PA\",\"language\":\"ak\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(134, 'default', 'created', 'App\\Models\\UserInfo', 'created', 32, NULL, NULL, '{\"attributes\":{\"id\":32,\"user_id\":32,\"avatar\":null,\"company\":\"Stehr-Moen\",\"phone\":\"701.694.1027\",\"website\":\"http:\\/\\/www.gutmann.info\\/\",\"country\":\"QA\",\"language\":\"pi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(135, 'default', 'created', 'App\\Models\\UserInfo', 'created', 33, NULL, NULL, '{\"attributes\":{\"id\":33,\"user_id\":33,\"avatar\":null,\"company\":\"Wunsch, Lesch and Little\",\"phone\":\"628-551-5050\",\"website\":\"http:\\/\\/www.moen.biz\\/impedit-accusantium-dolorem-qui-ea-vero-sint-blanditiis\",\"country\":\"TM\",\"language\":\"ps\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(136, 'default', 'created', 'App\\Models\\UserInfo', 'created', 34, NULL, NULL, '{\"attributes\":{\"id\":34,\"user_id\":34,\"avatar\":null,\"company\":\"McClure, Braun and Bashirian\",\"phone\":\"562-269-7745\",\"website\":\"https:\\/\\/crist.com\\/molestiae-laudantium-aliquam-est-sint.html\",\"country\":\"NI\",\"language\":\"or\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(137, 'default', 'created', 'App\\Models\\UserInfo', 'created', 35, NULL, NULL, '{\"attributes\":{\"id\":35,\"user_id\":35,\"avatar\":null,\"company\":\"Bergstrom Group\",\"phone\":\"+13808162577\",\"website\":\"http:\\/\\/www.mayert.biz\\/earum-et-tempore-quae\",\"country\":\"CZ\",\"language\":\"lv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(138, 'default', 'created', 'App\\Models\\UserInfo', 'created', 36, NULL, NULL, '{\"attributes\":{\"id\":36,\"user_id\":36,\"avatar\":null,\"company\":\"Langworth Ltd\",\"phone\":\"(725) 868-5735\",\"website\":\"http:\\/\\/crooks.biz\\/numquam-laborum-sequi-pariatur-ut-omnis\",\"country\":\"DJ\",\"language\":\"ik\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(139, 'default', 'created', 'App\\Models\\UserInfo', 'created', 37, NULL, NULL, '{\"attributes\":{\"id\":37,\"user_id\":37,\"avatar\":null,\"company\":\"Rath-Kassulke\",\"phone\":\"+13079491139\",\"website\":\"http:\\/\\/www.bartoletti.com\\/facilis-deserunt-molestiae-velit-non.html\",\"country\":\"DK\",\"language\":\"ks\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(140, 'default', 'created', 'App\\Models\\UserInfo', 'created', 38, NULL, NULL, '{\"attributes\":{\"id\":38,\"user_id\":38,\"avatar\":null,\"company\":\"Wiza LLC\",\"phone\":\"725-447-2591\",\"website\":\"http:\\/\\/www.kreiger.com\\/eos-est-illo-nulla-itaque-necessitatibus-repudiandae-officiis.html\",\"country\":\"EC\",\"language\":\"qu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(141, 'default', 'created', 'App\\Models\\UserInfo', 'created', 39, NULL, NULL, '{\"attributes\":{\"id\":39,\"user_id\":39,\"avatar\":null,\"company\":\"Durgan Ltd\",\"phone\":\"223-405-5697\",\"website\":\"http:\\/\\/lang.com\\/sed-omnis-quo-ex-qui\",\"country\":\"MQ\",\"language\":\"ky\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(142, 'default', 'created', 'App\\Models\\UserInfo', 'created', 40, NULL, NULL, '{\"attributes\":{\"id\":40,\"user_id\":40,\"avatar\":null,\"company\":\"Gutkowski Inc\",\"phone\":\"985-941-7339\",\"website\":\"https:\\/\\/www.vandervort.biz\\/sapiente-quo-sapiente-vero-vel-rerum\",\"country\":\"GE\",\"language\":\"it\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(143, 'default', 'created', 'App\\Models\\UserInfo', 'created', 41, NULL, NULL, '{\"attributes\":{\"id\":41,\"user_id\":41,\"avatar\":null,\"company\":\"Price PLC\",\"phone\":\"219-808-7763\",\"website\":\"http:\\/\\/carroll.org\\/omnis-provident-autem-et-laudantium-sunt\",\"country\":\"CY\",\"language\":\"ss\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(144, 'default', 'created', 'App\\Models\\UserInfo', 'created', 42, NULL, NULL, '{\"attributes\":{\"id\":42,\"user_id\":42,\"avatar\":null,\"company\":\"Labadie-Hodkiewicz\",\"phone\":\"351.465.2959\",\"website\":\"https:\\/\\/www.grimes.com\\/ducimus-officia-vero-error-ipsam\",\"country\":\"UZ\",\"language\":\"en\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(145, 'default', 'created', 'App\\Models\\UserInfo', 'created', 43, NULL, NULL, '{\"attributes\":{\"id\":43,\"user_id\":43,\"avatar\":null,\"company\":\"Grimes-Greenholt\",\"phone\":\"+1 (351) 844-8219\",\"website\":\"http:\\/\\/www.rohan.com\\/\",\"country\":\"CA\",\"language\":\"ne\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(146, 'default', 'created', 'App\\Models\\UserInfo', 'created', 44, NULL, NULL, '{\"attributes\":{\"id\":44,\"user_id\":44,\"avatar\":null,\"company\":\"Hartmann, Crooks and Hodkiewicz\",\"phone\":\"+16572310289\",\"website\":\"http:\\/\\/spencer.com\\/qui-eveniet-fuga-velit-quae-occaecati-distinctio-voluptatem\",\"country\":\"EE\",\"language\":\"cs\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(147, 'default', 'created', 'App\\Models\\UserInfo', 'created', 45, NULL, NULL, '{\"attributes\":{\"id\":45,\"user_id\":45,\"avatar\":null,\"company\":\"Deckow, Mueller and Turner\",\"phone\":\"1-602-614-7496\",\"website\":\"http:\\/\\/www.hintz.com\\/omnis-qui-ab-qui-quibusdam-vero-itaque-alias.html\",\"country\":\"ZA\",\"language\":\"su\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(148, 'default', 'created', 'App\\Models\\UserInfo', 'created', 46, NULL, NULL, '{\"attributes\":{\"id\":46,\"user_id\":46,\"avatar\":null,\"company\":\"Brown LLC\",\"phone\":\"706.873.7849\",\"website\":\"https:\\/\\/www.metz.com\\/quibusdam-numquam-voluptatem-rem-maxime-quos-sed-fugit-autem\",\"country\":\"BZ\",\"language\":\"se\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(149, 'default', 'created', 'App\\Models\\UserInfo', 'created', 47, NULL, NULL, '{\"attributes\":{\"id\":47,\"user_id\":47,\"avatar\":null,\"company\":\"Predovic-Tillman\",\"phone\":\"+1.469.318.8238\",\"website\":\"https:\\/\\/kautzer.com\\/laboriosam-rem-inventore-in-iusto-voluptatem.html\",\"country\":\"LI\",\"language\":\"cv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(150, 'default', 'created', 'App\\Models\\UserInfo', 'created', 48, NULL, NULL, '{\"attributes\":{\"id\":48,\"user_id\":48,\"avatar\":null,\"company\":\"Balistreri-Stanton\",\"phone\":\"(858) 826-4734\",\"website\":\"http:\\/\\/www.kautzer.org\\/officia-sint-nesciunt-et-omnis-exercitationem-quis-perspiciatis-voluptatibus.html\",\"country\":\"GE\",\"language\":\"te\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(151, 'default', 'created', 'App\\Models\\UserInfo', 'created', 49, NULL, NULL, '{\"attributes\":{\"id\":49,\"user_id\":49,\"avatar\":null,\"company\":\"Nicolas-McLaughlin\",\"phone\":\"1-937-565-7128\",\"website\":\"https:\\/\\/kreiger.com\\/dolor-nulla-nesciunt-quis-tempora-necessitatibus-enim-qui.html\",\"country\":\"SI\",\"language\":\"st\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(152, 'default', 'created', 'App\\Models\\UserInfo', 'created', 50, NULL, NULL, '{\"attributes\":{\"id\":50,\"user_id\":50,\"avatar\":null,\"company\":\"Hodkiewicz-Rice\",\"phone\":\"1-870-742-1873\",\"website\":\"http:\\/\\/www.vonrueden.com\\/praesentium-sit-ut-voluptatum-quae\",\"country\":\"JP\",\"language\":\"to\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(153, 'default', 'created', 'App\\Models\\UserInfo', 'created', 51, NULL, NULL, '{\"attributes\":{\"id\":51,\"user_id\":51,\"avatar\":null,\"company\":\"Rodriguez-Robel\",\"phone\":\"+1 (860) 497-6952\",\"website\":\"https:\\/\\/www.cartwright.com\\/necessitatibus-recusandae-voluptas-omnis-consectetur-voluptatem-ducimus-laudantium\",\"country\":\"CK\",\"language\":\"ff\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(154, 'default', 'created', 'App\\Models\\UserInfo', 'created', 52, NULL, NULL, '{\"attributes\":{\"id\":52,\"user_id\":52,\"avatar\":null,\"company\":\"Wisozk, O\'Hara and Hermann\",\"phone\":\"757.557.0993\",\"website\":\"http:\\/\\/www.shanahan.net\\/quam-nihil-qui-illum-quis\",\"country\":\"FR\",\"language\":\"fi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(155, 'default', 'created', 'App\\Models\\UserInfo', 'created', 53, NULL, NULL, '{\"attributes\":{\"id\":53,\"user_id\":53,\"avatar\":null,\"company\":\"Lubowitz-Swift\",\"phone\":\"+1 (443) 268-9700\",\"website\":\"https:\\/\\/ortiz.biz\\/molestiae-fugit-est-alias-quisquam-fugiat-magnam-rerum.html\",\"country\":\"NE\",\"language\":\"kw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(156, 'default', 'created', 'App\\Models\\UserInfo', 'created', 54, NULL, NULL, '{\"attributes\":{\"id\":54,\"user_id\":54,\"avatar\":null,\"company\":\"Ritchie-Schneider\",\"phone\":\"+18708464490\",\"website\":\"https:\\/\\/www.veum.net\\/consequatur-et-et-placeat-a\",\"country\":\"CR\",\"language\":\"ht\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(157, 'default', 'created', 'App\\Models\\UserInfo', 'created', 55, NULL, NULL, '{\"attributes\":{\"id\":55,\"user_id\":55,\"avatar\":null,\"company\":\"Franecki-Littel\",\"phone\":\"+1-540-326-5204\",\"website\":\"http:\\/\\/www.weissnat.com\\/eos-numquam-voluptatem-sint-voluptas-enim\",\"country\":\"AR\",\"language\":\"sr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(158, 'default', 'created', 'App\\Models\\UserInfo', 'created', 56, NULL, NULL, '{\"attributes\":{\"id\":56,\"user_id\":56,\"avatar\":null,\"company\":\"Altenwerth, Carter and Roob\",\"phone\":\"708-355-9725\",\"website\":\"http:\\/\\/hayes.org\\/\",\"country\":\"IL\",\"language\":\"gd\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(159, 'default', 'created', 'App\\Models\\UserInfo', 'created', 57, NULL, NULL, '{\"attributes\":{\"id\":57,\"user_id\":57,\"avatar\":null,\"company\":\"Keeling Ltd\",\"phone\":\"(657) 242-8735\",\"website\":\"http:\\/\\/fritsch.net\\/\",\"country\":\"PY\",\"language\":\"hz\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(160, 'default', 'created', 'App\\Models\\UserInfo', 'created', 58, NULL, NULL, '{\"attributes\":{\"id\":58,\"user_id\":58,\"avatar\":null,\"company\":\"Dare, Greenfelder and Cartwright\",\"phone\":\"+1.253.351.1265\",\"website\":\"http:\\/\\/www.dooley.com\\/facilis-et-aut-id\",\"country\":\"EC\",\"language\":\"cu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(161, 'default', 'created', 'App\\Models\\UserInfo', 'created', 59, NULL, NULL, '{\"attributes\":{\"id\":59,\"user_id\":59,\"avatar\":null,\"company\":\"Willms-Konopelski\",\"phone\":\"1-559-743-4122\",\"website\":\"https:\\/\\/swaniawski.com\\/voluptas-doloribus-voluptas-sit-ullam.html\",\"country\":\"JP\",\"language\":\"ur\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(162, 'default', 'created', 'App\\Models\\UserInfo', 'created', 60, NULL, NULL, '{\"attributes\":{\"id\":60,\"user_id\":60,\"avatar\":null,\"company\":\"Huel-Daugherty\",\"phone\":\"(425) 393-5322\",\"website\":\"http:\\/\\/beahan.com\\/\",\"country\":\"CA\",\"language\":\"fy\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(163, 'default', 'created', 'App\\Models\\UserInfo', 'created', 61, NULL, NULL, '{\"attributes\":{\"id\":61,\"user_id\":61,\"avatar\":null,\"company\":\"Treutel Ltd\",\"phone\":\"+1-779-492-7096\",\"website\":\"https:\\/\\/www.dubuque.com\\/earum-asperiores-aut-sed-laboriosam\",\"country\":\"NI\",\"language\":\"gn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(164, 'default', 'created', 'App\\Models\\UserInfo', 'created', 62, NULL, NULL, '{\"attributes\":{\"id\":62,\"user_id\":62,\"avatar\":null,\"company\":\"Cremin PLC\",\"phone\":\"986-589-2196\",\"website\":\"http:\\/\\/stanton.com\\/non-possimus-quam-harum-aut-a-similique\",\"country\":\"EG\",\"language\":\"ko\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(165, 'default', 'created', 'App\\Models\\UserInfo', 'created', 63, NULL, NULL, '{\"attributes\":{\"id\":63,\"user_id\":63,\"avatar\":null,\"company\":\"O\'Keefe, Gaylord and Stiedemann\",\"phone\":\"1-501-725-7187\",\"website\":\"http:\\/\\/www.medhurst.net\\/\",\"country\":\"BJ\",\"language\":\"hz\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(166, 'default', 'created', 'App\\Models\\UserInfo', 'created', 64, NULL, NULL, '{\"attributes\":{\"id\":64,\"user_id\":64,\"avatar\":null,\"company\":\"Rohan-Runolfsdottir\",\"phone\":\"+1.818.467.6683\",\"website\":\"http:\\/\\/jerde.com\\/officiis-nemo-dolorem-excepturi\",\"country\":\"FM\",\"language\":\"af\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(167, 'default', 'created', 'App\\Models\\UserInfo', 'created', 65, NULL, NULL, '{\"attributes\":{\"id\":65,\"user_id\":65,\"avatar\":null,\"company\":\"Wolf LLC\",\"phone\":\"+1-321-875-1048\",\"website\":\"http:\\/\\/lynch.com\\/consectetur-odio-sint-cumque-eaque.html\",\"country\":\"ME\",\"language\":\"sw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(168, 'default', 'created', 'App\\Models\\UserInfo', 'created', 66, NULL, NULL, '{\"attributes\":{\"id\":66,\"user_id\":66,\"avatar\":null,\"company\":\"Kling PLC\",\"phone\":\"909-869-2330\",\"website\":\"http:\\/\\/schmeler.com\\/assumenda-nam-aspernatur-enim-est-delectus-sed-voluptatem\",\"country\":\"KI\",\"language\":\"ie\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(169, 'default', 'created', 'App\\Models\\UserInfo', 'created', 67, NULL, NULL, '{\"attributes\":{\"id\":67,\"user_id\":67,\"avatar\":null,\"company\":\"Haley, Osinski and Von\",\"phone\":\"1-602-210-8939\",\"website\":\"http:\\/\\/langosh.org\\/corporis-qui-exercitationem-quod-fugiat-laudantium-reprehenderit.html\",\"country\":\"ER\",\"language\":\"vi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(170, 'default', 'created', 'App\\Models\\UserInfo', 'created', 68, NULL, NULL, '{\"attributes\":{\"id\":68,\"user_id\":68,\"avatar\":null,\"company\":\"Fisher PLC\",\"phone\":\"239.313.0694\",\"website\":\"http:\\/\\/zulauf.com\\/ea-dolorum-sint-similique\",\"country\":\"KI\",\"language\":\"be\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(171, 'default', 'created', 'App\\Models\\UserInfo', 'created', 69, NULL, NULL, '{\"attributes\":{\"id\":69,\"user_id\":69,\"avatar\":null,\"company\":\"Bosco LLC\",\"phone\":\"+16783570770\",\"website\":\"http:\\/\\/www.reilly.com\\/sequi-non-sint-non-natus\",\"country\":\"ML\",\"language\":\"kk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(172, 'default', 'created', 'App\\Models\\UserInfo', 'created', 70, NULL, NULL, '{\"attributes\":{\"id\":70,\"user_id\":70,\"avatar\":null,\"company\":\"Torp LLC\",\"phone\":\"1-434-816-4673\",\"website\":\"http:\\/\\/www.skiles.com\\/modi-recusandae-repellat-beatae-quos-suscipit-fugit-exercitationem-necessitatibus.html\",\"country\":\"HT\",\"language\":\"id\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(173, 'default', 'created', 'App\\Models\\UserInfo', 'created', 71, NULL, NULL, '{\"attributes\":{\"id\":71,\"user_id\":71,\"avatar\":null,\"company\":\"Runolfsson Ltd\",\"phone\":\"(283) 709-1775\",\"website\":\"https:\\/\\/hand.com\\/reprehenderit-aut-magni-et-ut-ut-vel.html\",\"country\":\"GD\",\"language\":\"nd\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(174, 'default', 'created', 'App\\Models\\UserInfo', 'created', 72, NULL, NULL, '{\"attributes\":{\"id\":72,\"user_id\":72,\"avatar\":null,\"company\":\"Greenholt-Prohaska\",\"phone\":\"541.910.9333\",\"website\":\"https:\\/\\/kiehn.info\\/consectetur-aliquid-aut-qui-labore.html\",\"country\":\"LT\",\"language\":\"xh\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(175, 'default', 'created', 'App\\Models\\UserInfo', 'created', 73, NULL, NULL, '{\"attributes\":{\"id\":73,\"user_id\":73,\"avatar\":null,\"company\":\"Ratke, Beatty and Cole\",\"phone\":\"(364) 738-5642\",\"website\":\"http:\\/\\/www.weimann.com\\/\",\"country\":\"SG\",\"language\":\"ee\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(176, 'default', 'created', 'App\\Models\\UserInfo', 'created', 74, NULL, NULL, '{\"attributes\":{\"id\":74,\"user_id\":74,\"avatar\":null,\"company\":\"Hackett Ltd\",\"phone\":\"(201) 658-4511\",\"website\":\"http:\\/\\/www.roberts.com\\/amet-occaecati-temporibus-tempore-officiis-molestias-reprehenderit.html\",\"country\":\"NI\",\"language\":\"km\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(177, 'default', 'created', 'App\\Models\\UserInfo', 'created', 75, NULL, NULL, '{\"attributes\":{\"id\":75,\"user_id\":75,\"avatar\":null,\"company\":\"Kuvalis LLC\",\"phone\":\"+1 (307) 840-2349\",\"website\":\"http:\\/\\/www.kuhlman.info\\/\",\"country\":\"BD\",\"language\":\"ks\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(178, 'default', 'created', 'App\\Models\\UserInfo', 'created', 76, NULL, NULL, '{\"attributes\":{\"id\":76,\"user_id\":76,\"avatar\":null,\"company\":\"Morar PLC\",\"phone\":\"+1-281-268-0472\",\"website\":\"http:\\/\\/www.mcdermott.org\\/\",\"country\":\"JP\",\"language\":\"kv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(179, 'default', 'created', 'App\\Models\\UserInfo', 'created', 77, NULL, NULL, '{\"attributes\":{\"id\":77,\"user_id\":77,\"avatar\":null,\"company\":\"Sawayn Inc\",\"phone\":\"540.823.5176\",\"website\":\"http:\\/\\/lubowitz.biz\\/magnam-non-minima-sint-aut-vel-mollitia\",\"country\":\"BT\",\"language\":\"fo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(180, 'default', 'created', 'App\\Models\\UserInfo', 'created', 78, NULL, NULL, '{\"attributes\":{\"id\":78,\"user_id\":78,\"avatar\":null,\"company\":\"Schultz-Wisozk\",\"phone\":\"(313) 432-7541\",\"website\":\"https:\\/\\/www.hamill.com\\/earum-architecto-eius-magni-repellendus-facilis\",\"country\":\"TF\",\"language\":\"vo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(181, 'default', 'created', 'App\\Models\\UserInfo', 'created', 79, NULL, NULL, '{\"attributes\":{\"id\":79,\"user_id\":79,\"avatar\":null,\"company\":\"Donnelly, Will and Kshlerin\",\"phone\":\"+1 (612) 844-6864\",\"website\":\"http:\\/\\/oberbrunner.info\\/deserunt-nisi-debitis-omnis-minima-sint-est\",\"country\":\"ST\",\"language\":\"ss\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(182, 'default', 'created', 'App\\Models\\UserInfo', 'created', 80, NULL, NULL, '{\"attributes\":{\"id\":80,\"user_id\":80,\"avatar\":null,\"company\":\"Kohler-Feeney\",\"phone\":\"+1-575-441-8828\",\"website\":\"http:\\/\\/www.hackett.biz\\/dignissimos-perferendis-dignissimos-repudiandae-dolorum-voluptatibus-tenetur-repellendus\",\"country\":\"CK\",\"language\":\"it\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(183, 'default', 'created', 'App\\Models\\UserInfo', 'created', 81, NULL, NULL, '{\"attributes\":{\"id\":81,\"user_id\":81,\"avatar\":null,\"company\":\"Walker-Kiehn\",\"phone\":\"+1-908-471-6801\",\"website\":\"http:\\/\\/www.daugherty.biz\\/\",\"country\":\"QA\",\"language\":\"ty\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(184, 'default', 'created', 'App\\Models\\UserInfo', 'created', 82, NULL, NULL, '{\"attributes\":{\"id\":82,\"user_id\":82,\"avatar\":null,\"company\":\"Koch, Klocko and Crooks\",\"phone\":\"1-248-533-6135\",\"website\":\"https:\\/\\/bednar.com\\/eveniet-voluptatibus-eos-cumque-et-aliquid-dolorem-autem-cupiditate.html\",\"country\":\"SN\",\"language\":\"eu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(185, 'default', 'created', 'App\\Models\\UserInfo', 'created', 83, NULL, NULL, '{\"attributes\":{\"id\":83,\"user_id\":83,\"avatar\":null,\"company\":\"Parisian-Wilkinson\",\"phone\":\"(820) 313-9835\",\"website\":\"https:\\/\\/bartoletti.com\\/beatae-eveniet-et-fugit-nemo-cupiditate-quisquam.html\",\"country\":\"HN\",\"language\":\"ro\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(186, 'default', 'created', 'App\\Models\\UserInfo', 'created', 84, NULL, NULL, '{\"attributes\":{\"id\":84,\"user_id\":84,\"avatar\":null,\"company\":\"Shields, Gleason and Hegmann\",\"phone\":\"1-425-566-5458\",\"website\":\"http:\\/\\/www.rohan.org\\/doloremque-modi-dolores-eum-pariatur-placeat-iste-iste-sint\",\"country\":\"SM\",\"language\":\"cs\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(187, 'default', 'created', 'App\\Models\\UserInfo', 'created', 85, NULL, NULL, '{\"attributes\":{\"id\":85,\"user_id\":85,\"avatar\":null,\"company\":\"Legros, Haag and Mills\",\"phone\":\"(323) 616-3184\",\"website\":\"http:\\/\\/zieme.com\\/\",\"country\":\"IL\",\"language\":\"ff\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(188, 'default', 'created', 'App\\Models\\UserInfo', 'created', 86, NULL, NULL, '{\"attributes\":{\"id\":86,\"user_id\":86,\"avatar\":null,\"company\":\"Prosacco, Little and Murray\",\"phone\":\"347.800.2252\",\"website\":\"http:\\/\\/www.hansen.com\\/\",\"country\":\"RS\",\"language\":\"uk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(189, 'default', 'created', 'App\\Models\\UserInfo', 'created', 87, NULL, NULL, '{\"attributes\":{\"id\":87,\"user_id\":87,\"avatar\":null,\"company\":\"Osinski Inc\",\"phone\":\"+14783423721\",\"website\":\"http:\\/\\/ziemann.biz\\/\",\"country\":\"BJ\",\"language\":\"ee\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(190, 'default', 'created', 'App\\Models\\UserInfo', 'created', 88, NULL, NULL, '{\"attributes\":{\"id\":88,\"user_id\":88,\"avatar\":null,\"company\":\"Considine and Sons\",\"phone\":\"+1-216-701-3632\",\"website\":\"http:\\/\\/www.green.biz\\/animi-qui-doloribus-provident-recusandae-quia-quidem-inventore.html\",\"country\":\"AU\",\"language\":\"su\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(191, 'default', 'created', 'App\\Models\\UserInfo', 'created', 89, NULL, NULL, '{\"attributes\":{\"id\":89,\"user_id\":89,\"avatar\":null,\"company\":\"Harvey, Flatley and Altenwerth\",\"phone\":\"1-567-483-5250\",\"website\":\"http:\\/\\/www.auer.com\\/ipsa-et-adipisci-fugiat-sint-doloremque-quos-reprehenderit.html\",\"country\":\"MC\",\"language\":\"hu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(192, 'default', 'created', 'App\\Models\\UserInfo', 'created', 90, NULL, NULL, '{\"attributes\":{\"id\":90,\"user_id\":90,\"avatar\":null,\"company\":\"Hirthe PLC\",\"phone\":\"608-294-0876\",\"website\":\"http:\\/\\/www.kemmer.com\\/quis-magni-voluptas-vel-molestias-tenetur.html\",\"country\":\"KE\",\"language\":\"uk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(193, 'default', 'created', 'App\\Models\\UserInfo', 'created', 91, NULL, NULL, '{\"attributes\":{\"id\":91,\"user_id\":91,\"avatar\":null,\"company\":\"Bartoletti-Hauck\",\"phone\":\"+1-612-539-4775\",\"website\":\"http:\\/\\/www.gislason.org\\/deserunt-consequuntur-commodi-et-in-voluptate.html\",\"country\":\"MN\",\"language\":\"tl\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(194, 'default', 'created', 'App\\Models\\UserInfo', 'created', 92, NULL, NULL, '{\"attributes\":{\"id\":92,\"user_id\":92,\"avatar\":null,\"company\":\"Hermiston, Corkery and Cronin\",\"phone\":\"+19525242128\",\"website\":\"http:\\/\\/gottlieb.net\\/aliquid-ullam-enim-debitis-perspiciatis-nesciunt-necessitatibus-enim\",\"country\":\"AO\",\"language\":\"za\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(195, 'default', 'created', 'App\\Models\\UserInfo', 'created', 93, NULL, NULL, '{\"attributes\":{\"id\":93,\"user_id\":93,\"avatar\":null,\"company\":\"Kuvalis, Keeling and Ullrich\",\"phone\":\"402.552.0885\",\"website\":\"http:\\/\\/www.pouros.biz\\/quia-repellendus-dolorem-vitae-deleniti\",\"country\":\"SM\",\"language\":\"sw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(196, 'default', 'created', 'App\\Models\\UserInfo', 'created', 94, NULL, NULL, '{\"attributes\":{\"id\":94,\"user_id\":94,\"avatar\":null,\"company\":\"Schmitt, Towne and Carter\",\"phone\":\"(410) 281-2156\",\"website\":\"http:\\/\\/reynolds.biz\\/commodi-rerum-deleniti-quaerat-aut-laboriosam-quia.html\",\"country\":\"HT\",\"language\":\"kl\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(197, 'default', 'created', 'App\\Models\\UserInfo', 'created', 95, NULL, NULL, '{\"attributes\":{\"id\":95,\"user_id\":95,\"avatar\":null,\"company\":\"Williamson-Hane\",\"phone\":\"1-480-968-3427\",\"website\":\"https:\\/\\/jenkins.net\\/consectetur-quis-quasi-omnis-tenetur-cupiditate-in.html\",\"country\":\"PG\",\"language\":\"mn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(198, 'default', 'created', 'App\\Models\\UserInfo', 'created', 96, NULL, NULL, '{\"attributes\":{\"id\":96,\"user_id\":96,\"avatar\":null,\"company\":\"McGlynn-Donnelly\",\"phone\":\"440.371.9553\",\"website\":\"http:\\/\\/www.crist.biz\\/\",\"country\":\"LC\",\"language\":\"jv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(199, 'default', 'created', 'App\\Models\\UserInfo', 'created', 97, NULL, NULL, '{\"attributes\":{\"id\":97,\"user_id\":97,\"avatar\":null,\"company\":\"Schinner LLC\",\"phone\":\"380.336.9448\",\"website\":\"http:\\/\\/hauck.com\\/non-ratione-sapiente-maiores-aut\",\"country\":\"LI\",\"language\":\"ku\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(200, 'default', 'created', 'App\\Models\\UserInfo', 'created', 98, NULL, NULL, '{\"attributes\":{\"id\":98,\"user_id\":98,\"avatar\":null,\"company\":\"Wilkinson, Nolan and Predovic\",\"phone\":\"+1-743-381-5031\",\"website\":\"http:\\/\\/www.spencer.org\\/expedita-molestiae-nulla-neque-accusantium-illo\",\"country\":\"GE\",\"language\":\"is\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(201, 'default', 'created', 'App\\Models\\UserInfo', 'created', 99, NULL, NULL, '{\"attributes\":{\"id\":99,\"user_id\":99,\"avatar\":null,\"company\":\"Hegmann-Heathcote\",\"phone\":\"865-879-1534\",\"website\":\"http:\\/\\/runolfsson.com\\/maxime-laborum-voluptatum-totam-itaque-aspernatur-sed-illum.html\",\"country\":\"MN\",\"language\":\"gu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(202, 'default', 'created', 'App\\Models\\UserInfo', 'created', 100, NULL, NULL, '{\"attributes\":{\"id\":100,\"user_id\":100,\"avatar\":null,\"company\":\"Block, Daugherty and Lynch\",\"phone\":\"+1.364.915.1307\",\"website\":\"http:\\/\\/www.maggio.com\\/\",\"country\":\"SS\",\"language\":\"so\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(203, 'default', 'created', 'App\\Models\\UserInfo', 'created', 101, NULL, NULL, '{\"attributes\":{\"id\":101,\"user_id\":101,\"avatar\":null,\"company\":\"Blick-Wiza\",\"phone\":\"+15019497591\",\"website\":\"http:\\/\\/www.klocko.net\\/\",\"country\":\"AI\",\"language\":\"oj\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(204, 'default', 'created', 'App\\Models\\UserInfo', 'created', 102, NULL, NULL, '{\"attributes\":{\"id\":102,\"user_id\":102,\"avatar\":null,\"company\":\"Reichert LLC\",\"phone\":\"520-424-4966\",\"website\":\"https:\\/\\/oconnell.com\\/qui-quis-minus-et.html\",\"country\":\"BD\",\"language\":\"lv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(205, 'default', 'created', 'App\\Models\\User', 'created', 103, NULL, NULL, '{\"attributes\":{\"id\":103,\"first_name\":\"MD\",\"last_name\":\"Masum\",\"email\":\"admin@masum.com\",\"email_verified_at\":null,\"password\":\"$2y$10$AfzXoCcyhMGbQnmKfBMOb.XjIEcEmIIFm7oA8T0CXSAKdl7btQwcG\",\"api_token\":null,\"remember_token\":null,\"created_at\":\"2023-01-31T09:59:20.000000Z\",\"updated_at\":\"2023-01-31T09:59:20.000000Z\"}}', NULL, '2023-01-31 03:59:20', '2023-01-31 03:59:20'),
(206, 'default', 'created', 'App\\Models\\UserInfo', 'created', 103, 'App\\Models\\User', 103, '{\"attributes\":{\"id\":103,\"user_id\":103,\"avatar\":\"images\\/m4QvXPPU5YcsWG4EOSVsQRBjI5eMIxL4CblmuFzN.png\",\"company\":null,\"phone\":\"016\",\"website\":null,\"country\":\"BD\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"currency\":null,\"communication\":{\"email\":\"1\",\"phone\":\"0\"},\"marketing\":0,\"created_at\":\"2023-01-31T10:05:00.000000Z\",\"updated_at\":\"2023-01-31T10:05:00.000000Z\"}}', NULL, '2023-01-31 04:05:00', '2023-01-31 04:05:00'),
(207, 'default', 'updated', 'App\\Models\\User', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"first_name\":\"GCL\",\"last_name\":\"Admin\",\"updated_at\":\"2023-01-31T10:56:45.000000Z\"},\"old\":{\"first_name\":\"MD\",\"last_name\":\"Masum\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-31 04:56:45', '2023-01-31 04:56:45'),
(208, 'default', 'updated', 'App\\Models\\UserInfo', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"avatar\":\"images\\/DA6oVfAqPOcSR4AjGbo1TryIlKbWmthbgXhgCtpx.png\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"communication\":{\"email\":\"0\",\"phone\":\"0\"},\"marketing\":0,\"updated_at\":\"2023-01-31T10:56:45.000000Z\"},\"old\":{\"avatar\":null,\"language\":\"ik\",\"timezone\":null,\"communication\":null,\"marketing\":null,\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-31 04:56:45', '2023-01-31 04:56:45'),
(209, 'default', 'created', 'App\\Models\\User', 'created', 104, NULL, NULL, '{\"attributes\":{\"id\":104,\"first_name\":\"Global\",\"last_name\":\"Admin\",\"email\":\"info@gslcorporate.com\",\"email_verified_at\":null,\"password\":\"$2y$10$OaFIVnac4qSoGiPY1ztUf.h7oNyGse1Ub9hGPrFEmVOnJIX5qTVye\",\"api_token\":null,\"remember_token\":null,\"created_at\":\"2023-01-31T12:24:50.000000Z\",\"updated_at\":\"2023-01-31T12:24:50.000000Z\"}}', NULL, '2023-01-31 06:24:50', '2023-01-31 06:24:50'),
(210, 'default', 'created', 'App\\Models\\UserInfo', 'created', 104, 'App\\Models\\User', 104, '{\"attributes\":{\"id\":104,\"user_id\":104,\"avatar\":\"images\\/8qtHFI0f9xOIss1lEz0Z62AGc3q3I5OyPrLF2ONx.png\",\"company\":null,\"phone\":\"01711 531786\",\"website\":null,\"country\":\"BD\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"currency\":null,\"communication\":{\"email\":\"0\",\"phone\":\"0\"},\"marketing\":0,\"created_at\":\"2023-01-31T12:27:48.000000Z\",\"updated_at\":\"2023-01-31T12:27:48.000000Z\"}}', NULL, '2023-01-31 06:27:48', '2023-01-31 06:27:48'),
(1, 'default', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{\"attributes\":{\"id\":1,\"first_name\":\"Hipolito\",\"last_name\":\"Aufderhar\",\"email\":\"demo@demo.com\",\"email_verified_at\":\"2023-01-30T11:05:03.000000Z\",\"password\":\"$2y$10$IjQ\\/OJA1sHLz1szIOR8OEu6q4.LcpSAOtoTmQP50U8NSQm\\/n.Hr8.\",\"api_token\":\"$2y$10$va5qgqH3w32oIcZhjZ0j2e6UKQN8X7mm3qr93iDW0aYBMaTZdtCTC\",\"remember_token\":null,\"created_at\":\"2023-01-30T11:05:03.000000Z\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-30 05:05:03', '2023-01-30 05:05:03'),
(2, 'default', 'created', 'App\\Models\\UserInfo', 'created', 1, NULL, NULL, '{\"attributes\":{\"id\":1,\"user_id\":1,\"avatar\":null,\"company\":\"Bradtke, Schaden and Greenfelder\",\"phone\":\"(443) 823-0276\",\"website\":\"http:\\/\\/www.stracke.org\\/velit-voluptatem-modi-sit-vel-tenetur\",\"country\":\"CK\",\"language\":\"ik\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:03.000000Z\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-30 05:05:03', '2023-01-30 05:05:03'),
(3, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{\"attributes\":{\"id\":2,\"first_name\":\"Easter\",\"last_name\":\"Rath\",\"email\":\"admin@demo.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$1MsuFFDinnOAsROoLuGULukkYyZecQVwndX8I132oq7Ex6PweRotS\",\"api_token\":\"$2y$10$xK9PVEBOAA0wFbQtYv6KougqQQ67xXqYinrAUcwVBJifQud7pGHUa\",\"remember_token\":null,\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(4, 'default', 'created', 'App\\Models\\UserInfo', 'created', 2, NULL, NULL, '{\"attributes\":{\"id\":2,\"user_id\":2,\"avatar\":null,\"company\":\"Herman PLC\",\"phone\":\"404-488-8064\",\"website\":\"http:\\/\\/prohaska.com\\/omnis-tempore-eveniet-possimus-explicabo-totam-qui-dolorum\",\"country\":\"AD\",\"language\":\"cy\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(5, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{\"attributes\":{\"id\":3,\"first_name\":\"Ethan\",\"last_name\":\"Okuneva\",\"email\":\"pmraz@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"mrkayf7HuG\",\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(6, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{\"attributes\":{\"id\":4,\"first_name\":\"Kaley\",\"last_name\":\"Friesen\",\"email\":\"quincy08@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"TtHHY3OgKO\",\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(7, 'default', 'created', 'App\\Models\\User', 'created', 5, NULL, NULL, '{\"attributes\":{\"id\":5,\"first_name\":\"Garret\",\"last_name\":\"Fisher\",\"email\":\"fflatley@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"s3fUCKxKnm\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(8, 'default', 'created', 'App\\Models\\User', 'created', 6, NULL, NULL, '{\"attributes\":{\"id\":6,\"first_name\":\"Hellen\",\"last_name\":\"Smith\",\"email\":\"kellie.bednar@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"LonPVwhUAo\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(9, 'default', 'created', 'App\\Models\\User', 'created', 7, NULL, NULL, '{\"attributes\":{\"id\":7,\"first_name\":\"Malinda\",\"last_name\":\"Hahn\",\"email\":\"yazmin23@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"XjogJZvnUg\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(10, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{\"attributes\":{\"id\":8,\"first_name\":\"Kim\",\"last_name\":\"Quigley\",\"email\":\"ndubuque@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"HwSwupDWEf\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(11, 'default', 'created', 'App\\Models\\User', 'created', 9, NULL, NULL, '{\"attributes\":{\"id\":9,\"first_name\":\"Bernadette\",\"last_name\":\"Ritchie\",\"email\":\"monserrat.schinner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"mmJmSKhUDq\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(12, 'default', 'created', 'App\\Models\\User', 'created', 10, NULL, NULL, '{\"attributes\":{\"id\":10,\"first_name\":\"Rory\",\"last_name\":\"Zulauf\",\"email\":\"rice.tate@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"fiqjbkDGQV\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(13, 'default', 'created', 'App\\Models\\User', 'created', 11, NULL, NULL, '{\"attributes\":{\"id\":11,\"first_name\":\"Gussie\",\"last_name\":\"Hayes\",\"email\":\"senger.timmy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UtaKOXLr7g\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(14, 'default', 'created', 'App\\Models\\User', 'created', 12, NULL, NULL, '{\"attributes\":{\"id\":12,\"first_name\":\"Trystan\",\"last_name\":\"Watsica\",\"email\":\"stacy78@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"3D0OK4iSJd\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(15, 'default', 'created', 'App\\Models\\User', 'created', 13, NULL, NULL, '{\"attributes\":{\"id\":13,\"first_name\":\"Zola\",\"last_name\":\"Schumm\",\"email\":\"lily.labadie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"7gbUeuiIEH\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(16, 'default', 'created', 'App\\Models\\User', 'created', 14, NULL, NULL, '{\"attributes\":{\"id\":14,\"first_name\":\"Savannah\",\"last_name\":\"Turner\",\"email\":\"kuhic.jayce@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"vab2Dhe5Va\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(17, 'default', 'created', 'App\\Models\\User', 'created', 15, NULL, NULL, '{\"attributes\":{\"id\":15,\"first_name\":\"Judge\",\"last_name\":\"Crona\",\"email\":\"geoffrey41@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"2vNdlvs79Y\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(18, 'default', 'created', 'App\\Models\\User', 'created', 16, NULL, NULL, '{\"attributes\":{\"id\":16,\"first_name\":\"Angie\",\"last_name\":\"Howell\",\"email\":\"kenny59@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"g5bwoW8BuS\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(19, 'default', 'created', 'App\\Models\\User', 'created', 17, NULL, NULL, '{\"attributes\":{\"id\":17,\"first_name\":\"Elwin\",\"last_name\":\"Hammes\",\"email\":\"alfredo59@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"VhKET9W8EN\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(20, 'default', 'created', 'App\\Models\\User', 'created', 18, NULL, NULL, '{\"attributes\":{\"id\":18,\"first_name\":\"Hailie\",\"last_name\":\"Conn\",\"email\":\"jace.quigley@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xXaP8P73y3\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(21, 'default', 'created', 'App\\Models\\User', 'created', 19, NULL, NULL, '{\"attributes\":{\"id\":19,\"first_name\":\"Juwan\",\"last_name\":\"Grimes\",\"email\":\"kohler.norris@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Wfq3z3tmye\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(22, 'default', 'created', 'App\\Models\\User', 'created', 20, NULL, NULL, '{\"attributes\":{\"id\":20,\"first_name\":\"Antonetta\",\"last_name\":\"Kirlin\",\"email\":\"harris.rosamond@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"P10us5pot6\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(23, 'default', 'created', 'App\\Models\\User', 'created', 21, NULL, NULL, '{\"attributes\":{\"id\":21,\"first_name\":\"Green\",\"last_name\":\"Rath\",\"email\":\"umckenzie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"gwaUUEQHIv\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(24, 'default', 'created', 'App\\Models\\User', 'created', 22, NULL, NULL, '{\"attributes\":{\"id\":22,\"first_name\":\"Vincenza\",\"last_name\":\"Von\",\"email\":\"violet.larson@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"tKkC19a3zp\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(25, 'default', 'created', 'App\\Models\\User', 'created', 23, NULL, NULL, '{\"attributes\":{\"id\":23,\"first_name\":\"Ladarius\",\"last_name\":\"Schaden\",\"email\":\"ilittel@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"MezWxI1rzd\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(26, 'default', 'created', 'App\\Models\\User', 'created', 24, NULL, NULL, '{\"attributes\":{\"id\":24,\"first_name\":\"Henriette\",\"last_name\":\"Lowe\",\"email\":\"tcollier@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qsC9Tp5NrM\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(27, 'default', 'created', 'App\\Models\\User', 'created', 25, NULL, NULL, '{\"attributes\":{\"id\":25,\"first_name\":\"Carlee\",\"last_name\":\"Turcotte\",\"email\":\"bogisich.pete@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"SczBhHe0nJ\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(28, 'default', 'created', 'App\\Models\\User', 'created', 26, NULL, NULL, '{\"attributes\":{\"id\":26,\"first_name\":\"Felicia\",\"last_name\":\"Sporer\",\"email\":\"eldora13@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rot02Hiukz\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(29, 'default', 'created', 'App\\Models\\User', 'created', 27, NULL, NULL, '{\"attributes\":{\"id\":27,\"first_name\":\"Alanna\",\"last_name\":\"Schaefer\",\"email\":\"derrick.beier@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"yup6EMcuEf\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(30, 'default', 'created', 'App\\Models\\User', 'created', 28, NULL, NULL, '{\"attributes\":{\"id\":28,\"first_name\":\"Christy\",\"last_name\":\"Runolfsson\",\"email\":\"emanuel.walsh@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"IcgX9O2NVn\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(31, 'default', 'created', 'App\\Models\\User', 'created', 29, NULL, NULL, '{\"attributes\":{\"id\":29,\"first_name\":\"Cecile\",\"last_name\":\"Becker\",\"email\":\"lemke.jadyn@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nTmZ4goJEv\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(32, 'default', 'created', 'App\\Models\\User', 'created', 30, NULL, NULL, '{\"attributes\":{\"id\":30,\"first_name\":\"Evan\",\"last_name\":\"Hudson\",\"email\":\"tthompson@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"SE77STcTKC\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(33, 'default', 'created', 'App\\Models\\User', 'created', 31, NULL, NULL, '{\"attributes\":{\"id\":31,\"first_name\":\"Demarco\",\"last_name\":\"Rath\",\"email\":\"jpfannerstill@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"8NgIfyBQES\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(34, 'default', 'created', 'App\\Models\\User', 'created', 32, NULL, NULL, '{\"attributes\":{\"id\":32,\"first_name\":\"Napoleon\",\"last_name\":\"Pollich\",\"email\":\"vallie.dubuque@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"EZoVRtkUCp\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(35, 'default', 'created', 'App\\Models\\User', 'created', 33, NULL, NULL, '{\"attributes\":{\"id\":33,\"first_name\":\"Hilbert\",\"last_name\":\"Lynch\",\"email\":\"golda93@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"RXAvhRqi4X\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(36, 'default', 'created', 'App\\Models\\User', 'created', 34, NULL, NULL, '{\"attributes\":{\"id\":34,\"first_name\":\"Melany\",\"last_name\":\"Kuvalis\",\"email\":\"krystel25@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qpUFG2DM4p\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(37, 'default', 'created', 'App\\Models\\User', 'created', 35, NULL, NULL, '{\"attributes\":{\"id\":35,\"first_name\":\"Marlee\",\"last_name\":\"Cormier\",\"email\":\"nadia.heller@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"PFEyDUjXgV\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(38, 'default', 'created', 'App\\Models\\User', 'created', 36, NULL, NULL, '{\"attributes\":{\"id\":36,\"first_name\":\"Jayden\",\"last_name\":\"Schoen\",\"email\":\"america.purdy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9poiXp5qbD\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(39, 'default', 'created', 'App\\Models\\User', 'created', 37, NULL, NULL, '{\"attributes\":{\"id\":37,\"first_name\":\"Alene\",\"last_name\":\"Mueller\",\"email\":\"pshields@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"k11ebpE9oG\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(40, 'default', 'created', 'App\\Models\\User', 'created', 38, NULL, NULL, '{\"attributes\":{\"id\":38,\"first_name\":\"Obie\",\"last_name\":\"Crist\",\"email\":\"dare.queenie@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ysGN2Yg4FW\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(41, 'default', 'created', 'App\\Models\\User', 'created', 39, NULL, NULL, '{\"attributes\":{\"id\":39,\"first_name\":\"Flossie\",\"last_name\":\"Collins\",\"email\":\"huel.grant@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xV3VQ6HkgJ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(42, 'default', 'created', 'App\\Models\\User', 'created', 40, NULL, NULL, '{\"attributes\":{\"id\":40,\"first_name\":\"Chasity\",\"last_name\":\"Ondricka\",\"email\":\"courtney65@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"lYYqHfhOAN\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(43, 'default', 'created', 'App\\Models\\User', 'created', 41, NULL, NULL, '{\"attributes\":{\"id\":41,\"first_name\":\"Gerry\",\"last_name\":\"Cummings\",\"email\":\"jarod.stanton@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"fNB77b2MoO\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(44, 'default', 'created', 'App\\Models\\User', 'created', 42, NULL, NULL, '{\"attributes\":{\"id\":42,\"first_name\":\"Ian\",\"last_name\":\"Walker\",\"email\":\"reichel.ford@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"zq1TGXCRdt\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(45, 'default', 'created', 'App\\Models\\User', 'created', 43, NULL, NULL, '{\"attributes\":{\"id\":43,\"first_name\":\"Moses\",\"last_name\":\"Ritchie\",\"email\":\"wunsch.lynn@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"t9zkKOM4WJ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(46, 'default', 'created', 'App\\Models\\User', 'created', 44, NULL, NULL, '{\"attributes\":{\"id\":44,\"first_name\":\"Fern\",\"last_name\":\"McDermott\",\"email\":\"murazik.rosemary@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"KrbuSUD1XQ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(47, 'default', 'created', 'App\\Models\\User', 'created', 45, NULL, NULL, '{\"attributes\":{\"id\":45,\"first_name\":\"Amani\",\"last_name\":\"West\",\"email\":\"hbernhard@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"1xL6mQN7FO\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(48, 'default', 'created', 'App\\Models\\User', 'created', 46, NULL, NULL, '{\"attributes\":{\"id\":46,\"first_name\":\"Leta\",\"last_name\":\"O\'Reilly\",\"email\":\"leora59@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"TU5fpNB9Nb\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(49, 'default', 'created', 'App\\Models\\User', 'created', 47, NULL, NULL, '{\"attributes\":{\"id\":47,\"first_name\":\"Hilda\",\"last_name\":\"Erdman\",\"email\":\"oswaldo.mann@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xXNp2FmCOo\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(50, 'default', 'created', 'App\\Models\\User', 'created', 48, NULL, NULL, '{\"attributes\":{\"id\":48,\"first_name\":\"Deborah\",\"last_name\":\"Considine\",\"email\":\"auer.stephanie@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"alDAhYCNuu\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(51, 'default', 'created', 'App\\Models\\User', 'created', 49, NULL, NULL, '{\"attributes\":{\"id\":49,\"first_name\":\"Garnett\",\"last_name\":\"Wiegand\",\"email\":\"bryce.denesik@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"tNNJfEkqMK\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(52, 'default', 'created', 'App\\Models\\User', 'created', 50, NULL, NULL, '{\"attributes\":{\"id\":50,\"first_name\":\"Roscoe\",\"last_name\":\"Feeney\",\"email\":\"wpadberg@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"kpe5IiXSVa\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(53, 'default', 'created', 'App\\Models\\User', 'created', 51, NULL, NULL, '{\"attributes\":{\"id\":51,\"first_name\":\"Naomie\",\"last_name\":\"Watsica\",\"email\":\"tleuschke@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"sWw2mC4a2S\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(54, 'default', 'created', 'App\\Models\\User', 'created', 52, NULL, NULL, '{\"attributes\":{\"id\":52,\"first_name\":\"Mercedes\",\"last_name\":\"Dickinson\",\"email\":\"cory.upton@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qeOebkITj7\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(55, 'default', 'created', 'App\\Models\\User', 'created', 53, NULL, NULL, '{\"attributes\":{\"id\":53,\"first_name\":\"Kieran\",\"last_name\":\"Kemmer\",\"email\":\"andy.cummerata@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"I3drbkDH4I\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(56, 'default', 'created', 'App\\Models\\User', 'created', 54, NULL, NULL, '{\"attributes\":{\"id\":54,\"first_name\":\"Rhea\",\"last_name\":\"Dickinson\",\"email\":\"swaniawski.griffin@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"WH6pX1HwR6\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(57, 'default', 'created', 'App\\Models\\User', 'created', 55, NULL, NULL, '{\"attributes\":{\"id\":55,\"first_name\":\"Hazel\",\"last_name\":\"Cronin\",\"email\":\"rutherford.kacie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9ULw9zXY2I\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(58, 'default', 'created', 'App\\Models\\User', 'created', 56, NULL, NULL, '{\"attributes\":{\"id\":56,\"first_name\":\"Maiya\",\"last_name\":\"Heidenreich\",\"email\":\"thaddeus59@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"krgAteBVIN\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(59, 'default', 'created', 'App\\Models\\User', 'created', 57, NULL, NULL, '{\"attributes\":{\"id\":57,\"first_name\":\"Winnifred\",\"last_name\":\"Bartoletti\",\"email\":\"garry05@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"aIxomeVwSJ\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(60, 'default', 'created', 'App\\Models\\User', 'created', 58, NULL, NULL, '{\"attributes\":{\"id\":58,\"first_name\":\"Sarina\",\"last_name\":\"Herman\",\"email\":\"layne.jacobi@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"3TuPDrdjpi\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(61, 'default', 'created', 'App\\Models\\User', 'created', 59, NULL, NULL, '{\"attributes\":{\"id\":59,\"first_name\":\"Hallie\",\"last_name\":\"Jerde\",\"email\":\"hprohaska@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"S4lM8Z5lhU\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(62, 'default', 'created', 'App\\Models\\User', 'created', 60, NULL, NULL, '{\"attributes\":{\"id\":60,\"first_name\":\"Kailyn\",\"last_name\":\"West\",\"email\":\"mhammes@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nv9ONrYWsc\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(63, 'default', 'created', 'App\\Models\\User', 'created', 61, NULL, NULL, '{\"attributes\":{\"id\":61,\"first_name\":\"Morris\",\"last_name\":\"Denesik\",\"email\":\"junius01@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xVm5v9SPeb\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(64, 'default', 'created', 'App\\Models\\User', 'created', 62, NULL, NULL, '{\"attributes\":{\"id\":62,\"first_name\":\"Cielo\",\"last_name\":\"Smitham\",\"email\":\"derek.mohr@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"OmitnGORr4\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(65, 'default', 'created', 'App\\Models\\User', 'created', 63, NULL, NULL, '{\"attributes\":{\"id\":63,\"first_name\":\"Shirley\",\"last_name\":\"Nader\",\"email\":\"serenity35@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"KEeYzIfshy\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(66, 'default', 'created', 'App\\Models\\User', 'created', 64, NULL, NULL, '{\"attributes\":{\"id\":64,\"first_name\":\"Beverly\",\"last_name\":\"Beatty\",\"email\":\"marguerite.schamberger@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"8gJidekKDF\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(67, 'default', 'created', 'App\\Models\\User', 'created', 65, NULL, NULL, '{\"attributes\":{\"id\":65,\"first_name\":\"Elvera\",\"last_name\":\"Ledner\",\"email\":\"reginald.maggio@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"vxAPW2mHuf\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(68, 'default', 'created', 'App\\Models\\User', 'created', 66, NULL, NULL, '{\"attributes\":{\"id\":66,\"first_name\":\"Shanelle\",\"last_name\":\"Terry\",\"email\":\"elody.oberbrunner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"dd6SGqwrRn\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(69, 'default', 'created', 'App\\Models\\User', 'created', 67, NULL, NULL, '{\"attributes\":{\"id\":67,\"first_name\":\"Pascale\",\"last_name\":\"Wilkinson\",\"email\":\"ford44@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rRypnV43F5\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(70, 'default', 'created', 'App\\Models\\User', 'created', 68, NULL, NULL, '{\"attributes\":{\"id\":68,\"first_name\":\"Destiny\",\"last_name\":\"Stamm\",\"email\":\"evie60@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ShsuLLom2q\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(71, 'default', 'created', 'App\\Models\\User', 'created', 69, NULL, NULL, '{\"attributes\":{\"id\":69,\"first_name\":\"Marilie\",\"last_name\":\"Skiles\",\"email\":\"dibbert.karina@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"GWaQ3tWxfL\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(72, 'default', 'created', 'App\\Models\\User', 'created', 70, NULL, NULL, '{\"attributes\":{\"id\":70,\"first_name\":\"Theodora\",\"last_name\":\"Beatty\",\"email\":\"kreiger.elissa@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"O3j9EZJlsC\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(73, 'default', 'created', 'App\\Models\\User', 'created', 71, NULL, NULL, '{\"attributes\":{\"id\":71,\"first_name\":\"Isabel\",\"last_name\":\"Gibson\",\"email\":\"ogleichner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Rm2bgN7hV7\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(74, 'default', 'created', 'App\\Models\\User', 'created', 72, NULL, NULL, '{\"attributes\":{\"id\":72,\"first_name\":\"Mallory\",\"last_name\":\"Heller\",\"email\":\"larkin.stone@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"7k4RknawAm\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(75, 'default', 'created', 'App\\Models\\User', 'created', 73, NULL, NULL, '{\"attributes\":{\"id\":73,\"first_name\":\"Sigmund\",\"last_name\":\"Ortiz\",\"email\":\"roslyn74@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"GCaqhXJbmV\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(76, 'default', 'created', 'App\\Models\\User', 'created', 74, NULL, NULL, '{\"attributes\":{\"id\":74,\"first_name\":\"Genoveva\",\"last_name\":\"Towne\",\"email\":\"alyson35@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"zCb74E1GPV\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(77, 'default', 'created', 'App\\Models\\User', 'created', 75, NULL, NULL, '{\"attributes\":{\"id\":75,\"first_name\":\"Astrid\",\"last_name\":\"Metz\",\"email\":\"theodora.schaefer@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"RPhowGhT6M\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(78, 'default', 'created', 'App\\Models\\User', 'created', 76, NULL, NULL, '{\"attributes\":{\"id\":76,\"first_name\":\"Myah\",\"last_name\":\"Steuber\",\"email\":\"carole89@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"d4CW2Qw5Iw\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(79, 'default', 'created', 'App\\Models\\User', 'created', 77, NULL, NULL, '{\"attributes\":{\"id\":77,\"first_name\":\"Verla\",\"last_name\":\"Gerhold\",\"email\":\"bogisich.nona@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"B5FdbRvX2n\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(80, 'default', 'created', 'App\\Models\\User', 'created', 78, NULL, NULL, '{\"attributes\":{\"id\":78,\"first_name\":\"Leola\",\"last_name\":\"Toy\",\"email\":\"drew63@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ENgDrPqWPe\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(81, 'default', 'created', 'App\\Models\\User', 'created', 79, NULL, NULL, '{\"attributes\":{\"id\":79,\"first_name\":\"Myrtle\",\"last_name\":\"Haley\",\"email\":\"ressie44@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"gJed20DG3Z\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(82, 'default', 'created', 'App\\Models\\User', 'created', 80, NULL, NULL, '{\"attributes\":{\"id\":80,\"first_name\":\"Benjamin\",\"last_name\":\"Jenkins\",\"email\":\"caitlyn.harvey@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rmAN2rO4ym\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(83, 'default', 'created', 'App\\Models\\User', 'created', 81, NULL, NULL, '{\"attributes\":{\"id\":81,\"first_name\":\"Wayne\",\"last_name\":\"Wilderman\",\"email\":\"schuppe.esperanza@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"uLkLMDY4Oq\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(84, 'default', 'created', 'App\\Models\\User', 'created', 82, NULL, NULL, '{\"attributes\":{\"id\":82,\"first_name\":\"Jules\",\"last_name\":\"Keebler\",\"email\":\"mellie18@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"AxZeuFKmfY\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(85, 'default', 'created', 'App\\Models\\User', 'created', 83, NULL, NULL, '{\"attributes\":{\"id\":83,\"first_name\":\"Zaria\",\"last_name\":\"Wisoky\",\"email\":\"monica90@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"n8FYas3Wzp\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(86, 'default', 'created', 'App\\Models\\User', 'created', 84, NULL, NULL, '{\"attributes\":{\"id\":84,\"first_name\":\"Coy\",\"last_name\":\"Mante\",\"email\":\"reynolds.ciara@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Hq7gz56jWg\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(87, 'default', 'created', 'App\\Models\\User', 'created', 85, NULL, NULL, '{\"attributes\":{\"id\":85,\"first_name\":\"Darlene\",\"last_name\":\"Breitenberg\",\"email\":\"gudrun.morissette@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ofJqanD3yo\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(88, 'default', 'created', 'App\\Models\\User', 'created', 86, NULL, NULL, '{\"attributes\":{\"id\":86,\"first_name\":\"Lane\",\"last_name\":\"Sawayn\",\"email\":\"zcasper@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Qll9tF02Ye\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(89, 'default', 'created', 'App\\Models\\User', 'created', 87, NULL, NULL, '{\"attributes\":{\"id\":87,\"first_name\":\"Lucile\",\"last_name\":\"Osinski\",\"email\":\"cooper15@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UiuaKq4Mtl\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(90, 'default', 'created', 'App\\Models\\User', 'created', 88, NULL, NULL, '{\"attributes\":{\"id\":88,\"first_name\":\"Claire\",\"last_name\":\"Dooley\",\"email\":\"tianna70@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nzzmvrqAiH\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(91, 'default', 'created', 'App\\Models\\User', 'created', 89, NULL, NULL, '{\"attributes\":{\"id\":89,\"first_name\":\"Yvette\",\"last_name\":\"Toy\",\"email\":\"marianna.jerde@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"wof9Yp8Wro\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(92, 'default', 'created', 'App\\Models\\User', 'created', 90, NULL, NULL, '{\"attributes\":{\"id\":90,\"first_name\":\"Evalyn\",\"last_name\":\"Abshire\",\"email\":\"chermiston@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"HTV2gdhxMm\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(93, 'default', 'created', 'App\\Models\\User', 'created', 91, NULL, NULL, '{\"attributes\":{\"id\":91,\"first_name\":\"Dasia\",\"last_name\":\"Bergnaum\",\"email\":\"xpaucek@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9Opm7FMNdg\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(94, 'default', 'created', 'App\\Models\\User', 'created', 92, NULL, NULL, '{\"attributes\":{\"id\":92,\"first_name\":\"Evangeline\",\"last_name\":\"Conroy\",\"email\":\"makenna53@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qG7WhhST3M\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(95, 'default', 'created', 'App\\Models\\User', 'created', 93, NULL, NULL, '{\"attributes\":{\"id\":93,\"first_name\":\"Gina\",\"last_name\":\"Hilpert\",\"email\":\"piper55@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"bsVFGZV5zT\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(96, 'default', 'created', 'App\\Models\\User', 'created', 94, NULL, NULL, '{\"attributes\":{\"id\":94,\"first_name\":\"Cristina\",\"last_name\":\"Koss\",\"email\":\"jgrady@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"QOjqDacaIg\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(97, 'default', 'created', 'App\\Models\\User', 'created', 95, NULL, NULL, '{\"attributes\":{\"id\":95,\"first_name\":\"Alfonso\",\"last_name\":\"Smith\",\"email\":\"areichert@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UUkcj5VYS5\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(98, 'default', 'created', 'App\\Models\\User', 'created', 96, NULL, NULL, '{\"attributes\":{\"id\":96,\"first_name\":\"Hilda\",\"last_name\":\"Crist\",\"email\":\"salma61@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"QKbmp8d3F5\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(99, 'default', 'created', 'App\\Models\\User', 'created', 97, NULL, NULL, '{\"attributes\":{\"id\":97,\"first_name\":\"Cindy\",\"last_name\":\"Bauch\",\"email\":\"schaefer.fernando@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Ip4gTYFvXf\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(100, 'default', 'created', 'App\\Models\\User', 'created', 98, NULL, NULL, '{\"attributes\":{\"id\":98,\"first_name\":\"Van\",\"last_name\":\"Bogan\",\"email\":\"jacobson.adolfo@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"yjJUSXrakq\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(101, 'default', 'created', 'App\\Models\\User', 'created', 99, NULL, NULL, '{\"attributes\":{\"id\":99,\"first_name\":\"Magnolia\",\"last_name\":\"Donnelly\",\"email\":\"vidal.hodkiewicz@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"eeatBeIL6b\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(102, 'default', 'created', 'App\\Models\\User', 'created', 100, NULL, NULL, '{\"attributes\":{\"id\":100,\"first_name\":\"Mozelle\",\"last_name\":\"Kutch\",\"email\":\"rconroy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"4aGgDbTzBK\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(103, 'default', 'created', 'App\\Models\\User', 'created', 101, NULL, NULL, '{\"attributes\":{\"id\":101,\"first_name\":\"Ashlynn\",\"last_name\":\"Treutel\",\"email\":\"schinner.thaddeus@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"IdQEP9xmzy\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(104, 'default', 'created', 'App\\Models\\User', 'created', 102, NULL, NULL, '{\"attributes\":{\"id\":102,\"first_name\":\"Mozell\",\"last_name\":\"Ruecker\",\"email\":\"zschmidt@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"h6zfzsNxNy\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(105, 'default', 'created', 'App\\Models\\UserInfo', 'created', 3, NULL, NULL, '{\"attributes\":{\"id\":3,\"user_id\":3,\"avatar\":null,\"company\":\"Halvorson Group\",\"phone\":\"+1 (430) 280-0462\",\"website\":\"http:\\/\\/www.zieme.info\\/veritatis-id-molestiae-ut-atque.html\",\"country\":\"BA\",\"language\":\"be\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(106, 'default', 'created', 'App\\Models\\UserInfo', 'created', 4, NULL, NULL, '{\"attributes\":{\"id\":4,\"user_id\":4,\"avatar\":null,\"company\":\"Von LLC\",\"phone\":\"845.285.4860\",\"website\":\"https:\\/\\/www.zboncak.com\\/reprehenderit-et-et-dolore-adipisci\",\"country\":\"IT\",\"language\":\"el\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(107, 'default', 'created', 'App\\Models\\UserInfo', 'created', 5, NULL, NULL, '{\"attributes\":{\"id\":5,\"user_id\":5,\"avatar\":null,\"company\":\"Upton-Stamm\",\"phone\":\"1-561-410-5112\",\"website\":\"http:\\/\\/www.marquardt.info\\/voluptatem-consequatur-repellat-aperiam-ipsam\",\"country\":\"RE\",\"language\":\"ho\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(108, 'default', 'created', 'App\\Models\\UserInfo', 'created', 6, NULL, NULL, '{\"attributes\":{\"id\":6,\"user_id\":6,\"avatar\":null,\"company\":\"Flatley, Howell and Lubowitz\",\"phone\":\"1-865-480-6435\",\"website\":\"https:\\/\\/deckow.com\\/aliquam-in-ut-autem-esse-voluptas-facilis.html\",\"country\":\"HT\",\"language\":\"ps\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(109, 'default', 'created', 'App\\Models\\UserInfo', 'created', 7, NULL, NULL, '{\"attributes\":{\"id\":7,\"user_id\":7,\"avatar\":null,\"company\":\"Watsica, Gutkowski and Blanda\",\"phone\":\"+1-445-910-2778\",\"website\":\"http:\\/\\/marquardt.com\\/\",\"country\":\"CF\",\"language\":\"ab\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(110, 'default', 'created', 'App\\Models\\UserInfo', 'created', 8, NULL, NULL, '{\"attributes\":{\"id\":8,\"user_id\":8,\"avatar\":null,\"company\":\"Deckow Ltd\",\"phone\":\"434.253.5867\",\"website\":\"http:\\/\\/www.balistreri.com\\/rem-exercitationem-illo-facilis-sunt.html\",\"country\":\"BN\",\"language\":\"iu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(111, 'default', 'created', 'App\\Models\\UserInfo', 'created', 9, NULL, NULL, '{\"attributes\":{\"id\":9,\"user_id\":9,\"avatar\":null,\"company\":\"Fisher, Mertz and Collins\",\"phone\":\"1-863-860-2159\",\"website\":\"https:\\/\\/www.hayes.com\\/placeat-quia-molestias-et-et-optio-minima-mollitia-excepturi\",\"country\":\"TT\",\"language\":\"ig\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(112, 'default', 'created', 'App\\Models\\UserInfo', 'created', 10, NULL, NULL, '{\"attributes\":{\"id\":10,\"user_id\":10,\"avatar\":null,\"company\":\"Bartoletti Group\",\"phone\":\"+1-248-350-9269\",\"website\":\"http:\\/\\/gottlieb.com\\/non-accusamus-non-aut-dolores-aliquid-incidunt\",\"country\":\"CI\",\"language\":\"li\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(113, 'default', 'created', 'App\\Models\\UserInfo', 'created', 11, NULL, NULL, '{\"attributes\":{\"id\":11,\"user_id\":11,\"avatar\":null,\"company\":\"Dickens-Parker\",\"phone\":\"+1.281.354.0408\",\"website\":\"http:\\/\\/kris.com\\/\",\"country\":\"BB\",\"language\":\"mg\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(114, 'default', 'created', 'App\\Models\\UserInfo', 'created', 12, NULL, NULL, '{\"attributes\":{\"id\":12,\"user_id\":12,\"avatar\":null,\"company\":\"Homenick Inc\",\"phone\":\"+1 (320) 544-0921\",\"website\":\"http:\\/\\/www.lang.com\\/omnis-temporibus-dignissimos-delectus-delectus-ipsam-omnis-iusto-quos\",\"country\":\"HR\",\"language\":\"tr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(115, 'default', 'created', 'App\\Models\\UserInfo', 'created', 13, NULL, NULL, '{\"attributes\":{\"id\":13,\"user_id\":13,\"avatar\":null,\"company\":\"Lemke LLC\",\"phone\":\"980-678-7084\",\"website\":\"http:\\/\\/www.rogahn.org\\/\",\"country\":\"KI\",\"language\":\"az\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(116, 'default', 'created', 'App\\Models\\UserInfo', 'created', 14, NULL, NULL, '{\"attributes\":{\"id\":14,\"user_id\":14,\"avatar\":null,\"company\":\"Braun Ltd\",\"phone\":\"1-629-228-6993\",\"website\":\"http:\\/\\/donnelly.com\\/\",\"country\":\"TZ\",\"language\":\"tt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(117, 'default', 'created', 'App\\Models\\UserInfo', 'created', 15, NULL, NULL, '{\"attributes\":{\"id\":15,\"user_id\":15,\"avatar\":null,\"company\":\"Reichel PLC\",\"phone\":\"1-769-782-1600\",\"website\":\"http:\\/\\/purdy.com\\/ullam-dolor-magni-tempora-eos\",\"country\":\"JP\",\"language\":\"sa\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(118, 'default', 'created', 'App\\Models\\UserInfo', 'created', 16, NULL, NULL, '{\"attributes\":{\"id\":16,\"user_id\":16,\"avatar\":null,\"company\":\"Windler and Sons\",\"phone\":\"239-860-4177\",\"website\":\"https:\\/\\/schumm.net\\/odit-quo-omnis-qui-beatae-saepe-voluptate.html\",\"country\":\"TH\",\"language\":\"na\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(119, 'default', 'created', 'App\\Models\\UserInfo', 'created', 17, NULL, NULL, '{\"attributes\":{\"id\":17,\"user_id\":17,\"avatar\":null,\"company\":\"Lowe, Vandervort and Feest\",\"phone\":\"707.763.6241\",\"website\":\"https:\\/\\/www.dach.biz\\/quisquam-placeat-expedita-quia\",\"country\":\"OM\",\"language\":\"nn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(120, 'default', 'created', 'App\\Models\\UserInfo', 'created', 18, NULL, NULL, '{\"attributes\":{\"id\":18,\"user_id\":18,\"avatar\":null,\"company\":\"Glover-Schneider\",\"phone\":\"1-979-819-8495\",\"website\":\"http:\\/\\/dare.org\\/\",\"country\":\"LC\",\"language\":\"an\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(121, 'default', 'created', 'App\\Models\\UserInfo', 'created', 19, NULL, NULL, '{\"attributes\":{\"id\":19,\"user_id\":19,\"avatar\":null,\"company\":\"Mohr-Gulgowski\",\"phone\":\"850.235.1441\",\"website\":\"https:\\/\\/cassin.biz\\/atque-rerum-soluta-facere.html\",\"country\":\"IL\",\"language\":\"sa\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(122, 'default', 'created', 'App\\Models\\UserInfo', 'created', 20, NULL, NULL, '{\"attributes\":{\"id\":20,\"user_id\":20,\"avatar\":null,\"company\":\"Schaefer, Turcotte and Quitzon\",\"phone\":\"208-900-5173\",\"website\":\"http:\\/\\/www.herman.info\\/\",\"country\":\"BI\",\"language\":\"fo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(123, 'default', 'created', 'App\\Models\\UserInfo', 'created', 21, NULL, NULL, '{\"attributes\":{\"id\":21,\"user_id\":21,\"avatar\":null,\"company\":\"Spinka-Hahn\",\"phone\":\"+1-512-875-0637\",\"website\":\"https:\\/\\/johns.org\\/perspiciatis-doloribus-ut-voluptas-omnis.html\",\"country\":\"DM\",\"language\":\"co\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(124, 'default', 'created', 'App\\Models\\UserInfo', 'created', 22, NULL, NULL, '{\"attributes\":{\"id\":22,\"user_id\":22,\"avatar\":null,\"company\":\"Boehm, Raynor and Cruickshank\",\"phone\":\"+16468822256\",\"website\":\"http:\\/\\/krajcik.info\\/dolores-nam-nulla-aperiam-eius\",\"country\":\"RU\",\"language\":\"lu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(125, 'default', 'created', 'App\\Models\\UserInfo', 'created', 23, NULL, NULL, '{\"attributes\":{\"id\":23,\"user_id\":23,\"avatar\":null,\"company\":\"Morar-Champlin\",\"phone\":\"+1-864-476-3168\",\"website\":\"https:\\/\\/www.schuster.com\\/voluptatem-ducimus-facere-cum-odit\",\"country\":\"CL\",\"language\":\"ig\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(126, 'default', 'created', 'App\\Models\\UserInfo', 'created', 24, NULL, NULL, '{\"attributes\":{\"id\":24,\"user_id\":24,\"avatar\":null,\"company\":\"Torp-Altenwerth\",\"phone\":\"+1-574-241-0944\",\"website\":\"https:\\/\\/goldner.com\\/et-aut-est-eius-a-reprehenderit-quod-quia.html\",\"country\":\"UM\",\"language\":\"ch\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(127, 'default', 'created', 'App\\Models\\UserInfo', 'created', 25, NULL, NULL, '{\"attributes\":{\"id\":25,\"user_id\":25,\"avatar\":null,\"company\":\"Harber-Renner\",\"phone\":\"812.284.0837\",\"website\":\"https:\\/\\/gerhold.com\\/sunt-ab-quidem-sunt-incidunt-quidem-sunt.html\",\"country\":\"LS\",\"language\":\"pi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(128, 'default', 'created', 'App\\Models\\UserInfo', 'created', 26, NULL, NULL, '{\"attributes\":{\"id\":26,\"user_id\":26,\"avatar\":null,\"company\":\"Schinner PLC\",\"phone\":\"508.672.3563\",\"website\":\"https:\\/\\/oberbrunner.com\\/est-quaerat-nam-quia-non.html\",\"country\":\"MV\",\"language\":\"mt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(129, 'default', 'created', 'App\\Models\\UserInfo', 'created', 27, NULL, NULL, '{\"attributes\":{\"id\":27,\"user_id\":27,\"avatar\":null,\"company\":\"Dibbert PLC\",\"phone\":\"386.765.2103\",\"website\":\"https:\\/\\/www.gislason.org\\/reiciendis-in-vero-suscipit-dolore-aspernatur-fugit-et\",\"country\":\"SI\",\"language\":\"mi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(130, 'default', 'created', 'App\\Models\\UserInfo', 'created', 28, NULL, NULL, '{\"attributes\":{\"id\":28,\"user_id\":28,\"avatar\":null,\"company\":\"Watsica, Maggio and Christiansen\",\"phone\":\"(845) 831-9784\",\"website\":\"http:\\/\\/www.gibson.org\\/rerum-distinctio-vel-hic\",\"country\":\"IM\",\"language\":\"nr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(131, 'default', 'created', 'App\\Models\\UserInfo', 'created', 29, NULL, NULL, '{\"attributes\":{\"id\":29,\"user_id\":29,\"avatar\":null,\"company\":\"Boyer PLC\",\"phone\":\"458-508-8836\",\"website\":\"http:\\/\\/www.runolfsson.com\\/voluptate-temporibus-minima-quia-reiciendis\",\"country\":\"VI\",\"language\":\"si\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(132, 'default', 'created', 'App\\Models\\UserInfo', 'created', 30, NULL, NULL, '{\"attributes\":{\"id\":30,\"user_id\":30,\"avatar\":null,\"company\":\"Lehner PLC\",\"phone\":\"+1-341-990-7613\",\"website\":\"http:\\/\\/www.berge.com\\/architecto-vel-rerum-fuga-iste-sunt-aliquid.html\",\"country\":\"MV\",\"language\":\"lt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(133, 'default', 'created', 'App\\Models\\UserInfo', 'created', 31, NULL, NULL, '{\"attributes\":{\"id\":31,\"user_id\":31,\"avatar\":null,\"company\":\"Cormier and Sons\",\"phone\":\"352.465.0917\",\"website\":\"http:\\/\\/www.kuhlman.com\\/saepe-doloribus-est-possimus-aut-ex-repellat\",\"country\":\"PA\",\"language\":\"ak\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(134, 'default', 'created', 'App\\Models\\UserInfo', 'created', 32, NULL, NULL, '{\"attributes\":{\"id\":32,\"user_id\":32,\"avatar\":null,\"company\":\"Stehr-Moen\",\"phone\":\"701.694.1027\",\"website\":\"http:\\/\\/www.gutmann.info\\/\",\"country\":\"QA\",\"language\":\"pi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(135, 'default', 'created', 'App\\Models\\UserInfo', 'created', 33, NULL, NULL, '{\"attributes\":{\"id\":33,\"user_id\":33,\"avatar\":null,\"company\":\"Wunsch, Lesch and Little\",\"phone\":\"628-551-5050\",\"website\":\"http:\\/\\/www.moen.biz\\/impedit-accusantium-dolorem-qui-ea-vero-sint-blanditiis\",\"country\":\"TM\",\"language\":\"ps\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(136, 'default', 'created', 'App\\Models\\UserInfo', 'created', 34, NULL, NULL, '{\"attributes\":{\"id\":34,\"user_id\":34,\"avatar\":null,\"company\":\"McClure, Braun and Bashirian\",\"phone\":\"562-269-7745\",\"website\":\"https:\\/\\/crist.com\\/molestiae-laudantium-aliquam-est-sint.html\",\"country\":\"NI\",\"language\":\"or\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(137, 'default', 'created', 'App\\Models\\UserInfo', 'created', 35, NULL, NULL, '{\"attributes\":{\"id\":35,\"user_id\":35,\"avatar\":null,\"company\":\"Bergstrom Group\",\"phone\":\"+13808162577\",\"website\":\"http:\\/\\/www.mayert.biz\\/earum-et-tempore-quae\",\"country\":\"CZ\",\"language\":\"lv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(138, 'default', 'created', 'App\\Models\\UserInfo', 'created', 36, NULL, NULL, '{\"attributes\":{\"id\":36,\"user_id\":36,\"avatar\":null,\"company\":\"Langworth Ltd\",\"phone\":\"(725) 868-5735\",\"website\":\"http:\\/\\/crooks.biz\\/numquam-laborum-sequi-pariatur-ut-omnis\",\"country\":\"DJ\",\"language\":\"ik\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(139, 'default', 'created', 'App\\Models\\UserInfo', 'created', 37, NULL, NULL, '{\"attributes\":{\"id\":37,\"user_id\":37,\"avatar\":null,\"company\":\"Rath-Kassulke\",\"phone\":\"+13079491139\",\"website\":\"http:\\/\\/www.bartoletti.com\\/facilis-deserunt-molestiae-velit-non.html\",\"country\":\"DK\",\"language\":\"ks\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(140, 'default', 'created', 'App\\Models\\UserInfo', 'created', 38, NULL, NULL, '{\"attributes\":{\"id\":38,\"user_id\":38,\"avatar\":null,\"company\":\"Wiza LLC\",\"phone\":\"725-447-2591\",\"website\":\"http:\\/\\/www.kreiger.com\\/eos-est-illo-nulla-itaque-necessitatibus-repudiandae-officiis.html\",\"country\":\"EC\",\"language\":\"qu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(141, 'default', 'created', 'App\\Models\\UserInfo', 'created', 39, NULL, NULL, '{\"attributes\":{\"id\":39,\"user_id\":39,\"avatar\":null,\"company\":\"Durgan Ltd\",\"phone\":\"223-405-5697\",\"website\":\"http:\\/\\/lang.com\\/sed-omnis-quo-ex-qui\",\"country\":\"MQ\",\"language\":\"ky\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(142, 'default', 'created', 'App\\Models\\UserInfo', 'created', 40, NULL, NULL, '{\"attributes\":{\"id\":40,\"user_id\":40,\"avatar\":null,\"company\":\"Gutkowski Inc\",\"phone\":\"985-941-7339\",\"website\":\"https:\\/\\/www.vandervort.biz\\/sapiente-quo-sapiente-vero-vel-rerum\",\"country\":\"GE\",\"language\":\"it\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(143, 'default', 'created', 'App\\Models\\UserInfo', 'created', 41, NULL, NULL, '{\"attributes\":{\"id\":41,\"user_id\":41,\"avatar\":null,\"company\":\"Price PLC\",\"phone\":\"219-808-7763\",\"website\":\"http:\\/\\/carroll.org\\/omnis-provident-autem-et-laudantium-sunt\",\"country\":\"CY\",\"language\":\"ss\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(144, 'default', 'created', 'App\\Models\\UserInfo', 'created', 42, NULL, NULL, '{\"attributes\":{\"id\":42,\"user_id\":42,\"avatar\":null,\"company\":\"Labadie-Hodkiewicz\",\"phone\":\"351.465.2959\",\"website\":\"https:\\/\\/www.grimes.com\\/ducimus-officia-vero-error-ipsam\",\"country\":\"UZ\",\"language\":\"en\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(145, 'default', 'created', 'App\\Models\\UserInfo', 'created', 43, NULL, NULL, '{\"attributes\":{\"id\":43,\"user_id\":43,\"avatar\":null,\"company\":\"Grimes-Greenholt\",\"phone\":\"+1 (351) 844-8219\",\"website\":\"http:\\/\\/www.rohan.com\\/\",\"country\":\"CA\",\"language\":\"ne\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(146, 'default', 'created', 'App\\Models\\UserInfo', 'created', 44, NULL, NULL, '{\"attributes\":{\"id\":44,\"user_id\":44,\"avatar\":null,\"company\":\"Hartmann, Crooks and Hodkiewicz\",\"phone\":\"+16572310289\",\"website\":\"http:\\/\\/spencer.com\\/qui-eveniet-fuga-velit-quae-occaecati-distinctio-voluptatem\",\"country\":\"EE\",\"language\":\"cs\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(147, 'default', 'created', 'App\\Models\\UserInfo', 'created', 45, NULL, NULL, '{\"attributes\":{\"id\":45,\"user_id\":45,\"avatar\":null,\"company\":\"Deckow, Mueller and Turner\",\"phone\":\"1-602-614-7496\",\"website\":\"http:\\/\\/www.hintz.com\\/omnis-qui-ab-qui-quibusdam-vero-itaque-alias.html\",\"country\":\"ZA\",\"language\":\"su\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(148, 'default', 'created', 'App\\Models\\UserInfo', 'created', 46, NULL, NULL, '{\"attributes\":{\"id\":46,\"user_id\":46,\"avatar\":null,\"company\":\"Brown LLC\",\"phone\":\"706.873.7849\",\"website\":\"https:\\/\\/www.metz.com\\/quibusdam-numquam-voluptatem-rem-maxime-quos-sed-fugit-autem\",\"country\":\"BZ\",\"language\":\"se\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(149, 'default', 'created', 'App\\Models\\UserInfo', 'created', 47, NULL, NULL, '{\"attributes\":{\"id\":47,\"user_id\":47,\"avatar\":null,\"company\":\"Predovic-Tillman\",\"phone\":\"+1.469.318.8238\",\"website\":\"https:\\/\\/kautzer.com\\/laboriosam-rem-inventore-in-iusto-voluptatem.html\",\"country\":\"LI\",\"language\":\"cv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(150, 'default', 'created', 'App\\Models\\UserInfo', 'created', 48, NULL, NULL, '{\"attributes\":{\"id\":48,\"user_id\":48,\"avatar\":null,\"company\":\"Balistreri-Stanton\",\"phone\":\"(858) 826-4734\",\"website\":\"http:\\/\\/www.kautzer.org\\/officia-sint-nesciunt-et-omnis-exercitationem-quis-perspiciatis-voluptatibus.html\",\"country\":\"GE\",\"language\":\"te\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(151, 'default', 'created', 'App\\Models\\UserInfo', 'created', 49, NULL, NULL, '{\"attributes\":{\"id\":49,\"user_id\":49,\"avatar\":null,\"company\":\"Nicolas-McLaughlin\",\"phone\":\"1-937-565-7128\",\"website\":\"https:\\/\\/kreiger.com\\/dolor-nulla-nesciunt-quis-tempora-necessitatibus-enim-qui.html\",\"country\":\"SI\",\"language\":\"st\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(152, 'default', 'created', 'App\\Models\\UserInfo', 'created', 50, NULL, NULL, '{\"attributes\":{\"id\":50,\"user_id\":50,\"avatar\":null,\"company\":\"Hodkiewicz-Rice\",\"phone\":\"1-870-742-1873\",\"website\":\"http:\\/\\/www.vonrueden.com\\/praesentium-sit-ut-voluptatum-quae\",\"country\":\"JP\",\"language\":\"to\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(153, 'default', 'created', 'App\\Models\\UserInfo', 'created', 51, NULL, NULL, '{\"attributes\":{\"id\":51,\"user_id\":51,\"avatar\":null,\"company\":\"Rodriguez-Robel\",\"phone\":\"+1 (860) 497-6952\",\"website\":\"https:\\/\\/www.cartwright.com\\/necessitatibus-recusandae-voluptas-omnis-consectetur-voluptatem-ducimus-laudantium\",\"country\":\"CK\",\"language\":\"ff\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(154, 'default', 'created', 'App\\Models\\UserInfo', 'created', 52, NULL, NULL, '{\"attributes\":{\"id\":52,\"user_id\":52,\"avatar\":null,\"company\":\"Wisozk, O\'Hara and Hermann\",\"phone\":\"757.557.0993\",\"website\":\"http:\\/\\/www.shanahan.net\\/quam-nihil-qui-illum-quis\",\"country\":\"FR\",\"language\":\"fi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(155, 'default', 'created', 'App\\Models\\UserInfo', 'created', 53, NULL, NULL, '{\"attributes\":{\"id\":53,\"user_id\":53,\"avatar\":null,\"company\":\"Lubowitz-Swift\",\"phone\":\"+1 (443) 268-9700\",\"website\":\"https:\\/\\/ortiz.biz\\/molestiae-fugit-est-alias-quisquam-fugiat-magnam-rerum.html\",\"country\":\"NE\",\"language\":\"kw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(156, 'default', 'created', 'App\\Models\\UserInfo', 'created', 54, NULL, NULL, '{\"attributes\":{\"id\":54,\"user_id\":54,\"avatar\":null,\"company\":\"Ritchie-Schneider\",\"phone\":\"+18708464490\",\"website\":\"https:\\/\\/www.veum.net\\/consequatur-et-et-placeat-a\",\"country\":\"CR\",\"language\":\"ht\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(157, 'default', 'created', 'App\\Models\\UserInfo', 'created', 55, NULL, NULL, '{\"attributes\":{\"id\":55,\"user_id\":55,\"avatar\":null,\"company\":\"Franecki-Littel\",\"phone\":\"+1-540-326-5204\",\"website\":\"http:\\/\\/www.weissnat.com\\/eos-numquam-voluptatem-sint-voluptas-enim\",\"country\":\"AR\",\"language\":\"sr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(158, 'default', 'created', 'App\\Models\\UserInfo', 'created', 56, NULL, NULL, '{\"attributes\":{\"id\":56,\"user_id\":56,\"avatar\":null,\"company\":\"Altenwerth, Carter and Roob\",\"phone\":\"708-355-9725\",\"website\":\"http:\\/\\/hayes.org\\/\",\"country\":\"IL\",\"language\":\"gd\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(159, 'default', 'created', 'App\\Models\\UserInfo', 'created', 57, NULL, NULL, '{\"attributes\":{\"id\":57,\"user_id\":57,\"avatar\":null,\"company\":\"Keeling Ltd\",\"phone\":\"(657) 242-8735\",\"website\":\"http:\\/\\/fritsch.net\\/\",\"country\":\"PY\",\"language\":\"hz\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(160, 'default', 'created', 'App\\Models\\UserInfo', 'created', 58, NULL, NULL, '{\"attributes\":{\"id\":58,\"user_id\":58,\"avatar\":null,\"company\":\"Dare, Greenfelder and Cartwright\",\"phone\":\"+1.253.351.1265\",\"website\":\"http:\\/\\/www.dooley.com\\/facilis-et-aut-id\",\"country\":\"EC\",\"language\":\"cu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(161, 'default', 'created', 'App\\Models\\UserInfo', 'created', 59, NULL, NULL, '{\"attributes\":{\"id\":59,\"user_id\":59,\"avatar\":null,\"company\":\"Willms-Konopelski\",\"phone\":\"1-559-743-4122\",\"website\":\"https:\\/\\/swaniawski.com\\/voluptas-doloribus-voluptas-sit-ullam.html\",\"country\":\"JP\",\"language\":\"ur\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(162, 'default', 'created', 'App\\Models\\UserInfo', 'created', 60, NULL, NULL, '{\"attributes\":{\"id\":60,\"user_id\":60,\"avatar\":null,\"company\":\"Huel-Daugherty\",\"phone\":\"(425) 393-5322\",\"website\":\"http:\\/\\/beahan.com\\/\",\"country\":\"CA\",\"language\":\"fy\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(163, 'default', 'created', 'App\\Models\\UserInfo', 'created', 61, NULL, NULL, '{\"attributes\":{\"id\":61,\"user_id\":61,\"avatar\":null,\"company\":\"Treutel Ltd\",\"phone\":\"+1-779-492-7096\",\"website\":\"https:\\/\\/www.dubuque.com\\/earum-asperiores-aut-sed-laboriosam\",\"country\":\"NI\",\"language\":\"gn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(164, 'default', 'created', 'App\\Models\\UserInfo', 'created', 62, NULL, NULL, '{\"attributes\":{\"id\":62,\"user_id\":62,\"avatar\":null,\"company\":\"Cremin PLC\",\"phone\":\"986-589-2196\",\"website\":\"http:\\/\\/stanton.com\\/non-possimus-quam-harum-aut-a-similique\",\"country\":\"EG\",\"language\":\"ko\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(165, 'default', 'created', 'App\\Models\\UserInfo', 'created', 63, NULL, NULL, '{\"attributes\":{\"id\":63,\"user_id\":63,\"avatar\":null,\"company\":\"O\'Keefe, Gaylord and Stiedemann\",\"phone\":\"1-501-725-7187\",\"website\":\"http:\\/\\/www.medhurst.net\\/\",\"country\":\"BJ\",\"language\":\"hz\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(166, 'default', 'created', 'App\\Models\\UserInfo', 'created', 64, NULL, NULL, '{\"attributes\":{\"id\":64,\"user_id\":64,\"avatar\":null,\"company\":\"Rohan-Runolfsdottir\",\"phone\":\"+1.818.467.6683\",\"website\":\"http:\\/\\/jerde.com\\/officiis-nemo-dolorem-excepturi\",\"country\":\"FM\",\"language\":\"af\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(167, 'default', 'created', 'App\\Models\\UserInfo', 'created', 65, NULL, NULL, '{\"attributes\":{\"id\":65,\"user_id\":65,\"avatar\":null,\"company\":\"Wolf LLC\",\"phone\":\"+1-321-875-1048\",\"website\":\"http:\\/\\/lynch.com\\/consectetur-odio-sint-cumque-eaque.html\",\"country\":\"ME\",\"language\":\"sw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(168, 'default', 'created', 'App\\Models\\UserInfo', 'created', 66, NULL, NULL, '{\"attributes\":{\"id\":66,\"user_id\":66,\"avatar\":null,\"company\":\"Kling PLC\",\"phone\":\"909-869-2330\",\"website\":\"http:\\/\\/schmeler.com\\/assumenda-nam-aspernatur-enim-est-delectus-sed-voluptatem\",\"country\":\"KI\",\"language\":\"ie\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(169, 'default', 'created', 'App\\Models\\UserInfo', 'created', 67, NULL, NULL, '{\"attributes\":{\"id\":67,\"user_id\":67,\"avatar\":null,\"company\":\"Haley, Osinski and Von\",\"phone\":\"1-602-210-8939\",\"website\":\"http:\\/\\/langosh.org\\/corporis-qui-exercitationem-quod-fugiat-laudantium-reprehenderit.html\",\"country\":\"ER\",\"language\":\"vi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(170, 'default', 'created', 'App\\Models\\UserInfo', 'created', 68, NULL, NULL, '{\"attributes\":{\"id\":68,\"user_id\":68,\"avatar\":null,\"company\":\"Fisher PLC\",\"phone\":\"239.313.0694\",\"website\":\"http:\\/\\/zulauf.com\\/ea-dolorum-sint-similique\",\"country\":\"KI\",\"language\":\"be\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(171, 'default', 'created', 'App\\Models\\UserInfo', 'created', 69, NULL, NULL, '{\"attributes\":{\"id\":69,\"user_id\":69,\"avatar\":null,\"company\":\"Bosco LLC\",\"phone\":\"+16783570770\",\"website\":\"http:\\/\\/www.reilly.com\\/sequi-non-sint-non-natus\",\"country\":\"ML\",\"language\":\"kk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(172, 'default', 'created', 'App\\Models\\UserInfo', 'created', 70, NULL, NULL, '{\"attributes\":{\"id\":70,\"user_id\":70,\"avatar\":null,\"company\":\"Torp LLC\",\"phone\":\"1-434-816-4673\",\"website\":\"http:\\/\\/www.skiles.com\\/modi-recusandae-repellat-beatae-quos-suscipit-fugit-exercitationem-necessitatibus.html\",\"country\":\"HT\",\"language\":\"id\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(173, 'default', 'created', 'App\\Models\\UserInfo', 'created', 71, NULL, NULL, '{\"attributes\":{\"id\":71,\"user_id\":71,\"avatar\":null,\"company\":\"Runolfsson Ltd\",\"phone\":\"(283) 709-1775\",\"website\":\"https:\\/\\/hand.com\\/reprehenderit-aut-magni-et-ut-ut-vel.html\",\"country\":\"GD\",\"language\":\"nd\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(174, 'default', 'created', 'App\\Models\\UserInfo', 'created', 72, NULL, NULL, '{\"attributes\":{\"id\":72,\"user_id\":72,\"avatar\":null,\"company\":\"Greenholt-Prohaska\",\"phone\":\"541.910.9333\",\"website\":\"https:\\/\\/kiehn.info\\/consectetur-aliquid-aut-qui-labore.html\",\"country\":\"LT\",\"language\":\"xh\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(175, 'default', 'created', 'App\\Models\\UserInfo', 'created', 73, NULL, NULL, '{\"attributes\":{\"id\":73,\"user_id\":73,\"avatar\":null,\"company\":\"Ratke, Beatty and Cole\",\"phone\":\"(364) 738-5642\",\"website\":\"http:\\/\\/www.weimann.com\\/\",\"country\":\"SG\",\"language\":\"ee\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(176, 'default', 'created', 'App\\Models\\UserInfo', 'created', 74, NULL, NULL, '{\"attributes\":{\"id\":74,\"user_id\":74,\"avatar\":null,\"company\":\"Hackett Ltd\",\"phone\":\"(201) 658-4511\",\"website\":\"http:\\/\\/www.roberts.com\\/amet-occaecati-temporibus-tempore-officiis-molestias-reprehenderit.html\",\"country\":\"NI\",\"language\":\"km\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(177, 'default', 'created', 'App\\Models\\UserInfo', 'created', 75, NULL, NULL, '{\"attributes\":{\"id\":75,\"user_id\":75,\"avatar\":null,\"company\":\"Kuvalis LLC\",\"phone\":\"+1 (307) 840-2349\",\"website\":\"http:\\/\\/www.kuhlman.info\\/\",\"country\":\"BD\",\"language\":\"ks\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(178, 'default', 'created', 'App\\Models\\UserInfo', 'created', 76, NULL, NULL, '{\"attributes\":{\"id\":76,\"user_id\":76,\"avatar\":null,\"company\":\"Morar PLC\",\"phone\":\"+1-281-268-0472\",\"website\":\"http:\\/\\/www.mcdermott.org\\/\",\"country\":\"JP\",\"language\":\"kv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(179, 'default', 'created', 'App\\Models\\UserInfo', 'created', 77, NULL, NULL, '{\"attributes\":{\"id\":77,\"user_id\":77,\"avatar\":null,\"company\":\"Sawayn Inc\",\"phone\":\"540.823.5176\",\"website\":\"http:\\/\\/lubowitz.biz\\/magnam-non-minima-sint-aut-vel-mollitia\",\"country\":\"BT\",\"language\":\"fo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(180, 'default', 'created', 'App\\Models\\UserInfo', 'created', 78, NULL, NULL, '{\"attributes\":{\"id\":78,\"user_id\":78,\"avatar\":null,\"company\":\"Schultz-Wisozk\",\"phone\":\"(313) 432-7541\",\"website\":\"https:\\/\\/www.hamill.com\\/earum-architecto-eius-magni-repellendus-facilis\",\"country\":\"TF\",\"language\":\"vo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(181, 'default', 'created', 'App\\Models\\UserInfo', 'created', 79, NULL, NULL, '{\"attributes\":{\"id\":79,\"user_id\":79,\"avatar\":null,\"company\":\"Donnelly, Will and Kshlerin\",\"phone\":\"+1 (612) 844-6864\",\"website\":\"http:\\/\\/oberbrunner.info\\/deserunt-nisi-debitis-omnis-minima-sint-est\",\"country\":\"ST\",\"language\":\"ss\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(182, 'default', 'created', 'App\\Models\\UserInfo', 'created', 80, NULL, NULL, '{\"attributes\":{\"id\":80,\"user_id\":80,\"avatar\":null,\"company\":\"Kohler-Feeney\",\"phone\":\"+1-575-441-8828\",\"website\":\"http:\\/\\/www.hackett.biz\\/dignissimos-perferendis-dignissimos-repudiandae-dolorum-voluptatibus-tenetur-repellendus\",\"country\":\"CK\",\"language\":\"it\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(183, 'default', 'created', 'App\\Models\\UserInfo', 'created', 81, NULL, NULL, '{\"attributes\":{\"id\":81,\"user_id\":81,\"avatar\":null,\"company\":\"Walker-Kiehn\",\"phone\":\"+1-908-471-6801\",\"website\":\"http:\\/\\/www.daugherty.biz\\/\",\"country\":\"QA\",\"language\":\"ty\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(184, 'default', 'created', 'App\\Models\\UserInfo', 'created', 82, NULL, NULL, '{\"attributes\":{\"id\":82,\"user_id\":82,\"avatar\":null,\"company\":\"Koch, Klocko and Crooks\",\"phone\":\"1-248-533-6135\",\"website\":\"https:\\/\\/bednar.com\\/eveniet-voluptatibus-eos-cumque-et-aliquid-dolorem-autem-cupiditate.html\",\"country\":\"SN\",\"language\":\"eu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(185, 'default', 'created', 'App\\Models\\UserInfo', 'created', 83, NULL, NULL, '{\"attributes\":{\"id\":83,\"user_id\":83,\"avatar\":null,\"company\":\"Parisian-Wilkinson\",\"phone\":\"(820) 313-9835\",\"website\":\"https:\\/\\/bartoletti.com\\/beatae-eveniet-et-fugit-nemo-cupiditate-quisquam.html\",\"country\":\"HN\",\"language\":\"ro\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(186, 'default', 'created', 'App\\Models\\UserInfo', 'created', 84, NULL, NULL, '{\"attributes\":{\"id\":84,\"user_id\":84,\"avatar\":null,\"company\":\"Shields, Gleason and Hegmann\",\"phone\":\"1-425-566-5458\",\"website\":\"http:\\/\\/www.rohan.org\\/doloremque-modi-dolores-eum-pariatur-placeat-iste-iste-sint\",\"country\":\"SM\",\"language\":\"cs\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(187, 'default', 'created', 'App\\Models\\UserInfo', 'created', 85, NULL, NULL, '{\"attributes\":{\"id\":85,\"user_id\":85,\"avatar\":null,\"company\":\"Legros, Haag and Mills\",\"phone\":\"(323) 616-3184\",\"website\":\"http:\\/\\/zieme.com\\/\",\"country\":\"IL\",\"language\":\"ff\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(188, 'default', 'created', 'App\\Models\\UserInfo', 'created', 86, NULL, NULL, '{\"attributes\":{\"id\":86,\"user_id\":86,\"avatar\":null,\"company\":\"Prosacco, Little and Murray\",\"phone\":\"347.800.2252\",\"website\":\"http:\\/\\/www.hansen.com\\/\",\"country\":\"RS\",\"language\":\"uk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(189, 'default', 'created', 'App\\Models\\UserInfo', 'created', 87, NULL, NULL, '{\"attributes\":{\"id\":87,\"user_id\":87,\"avatar\":null,\"company\":\"Osinski Inc\",\"phone\":\"+14783423721\",\"website\":\"http:\\/\\/ziemann.biz\\/\",\"country\":\"BJ\",\"language\":\"ee\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(190, 'default', 'created', 'App\\Models\\UserInfo', 'created', 88, NULL, NULL, '{\"attributes\":{\"id\":88,\"user_id\":88,\"avatar\":null,\"company\":\"Considine and Sons\",\"phone\":\"+1-216-701-3632\",\"website\":\"http:\\/\\/www.green.biz\\/animi-qui-doloribus-provident-recusandae-quia-quidem-inventore.html\",\"country\":\"AU\",\"language\":\"su\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(191, 'default', 'created', 'App\\Models\\UserInfo', 'created', 89, NULL, NULL, '{\"attributes\":{\"id\":89,\"user_id\":89,\"avatar\":null,\"company\":\"Harvey, Flatley and Altenwerth\",\"phone\":\"1-567-483-5250\",\"website\":\"http:\\/\\/www.auer.com\\/ipsa-et-adipisci-fugiat-sint-doloremque-quos-reprehenderit.html\",\"country\":\"MC\",\"language\":\"hu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(192, 'default', 'created', 'App\\Models\\UserInfo', 'created', 90, NULL, NULL, '{\"attributes\":{\"id\":90,\"user_id\":90,\"avatar\":null,\"company\":\"Hirthe PLC\",\"phone\":\"608-294-0876\",\"website\":\"http:\\/\\/www.kemmer.com\\/quis-magni-voluptas-vel-molestias-tenetur.html\",\"country\":\"KE\",\"language\":\"uk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(193, 'default', 'created', 'App\\Models\\UserInfo', 'created', 91, NULL, NULL, '{\"attributes\":{\"id\":91,\"user_id\":91,\"avatar\":null,\"company\":\"Bartoletti-Hauck\",\"phone\":\"+1-612-539-4775\",\"website\":\"http:\\/\\/www.gislason.org\\/deserunt-consequuntur-commodi-et-in-voluptate.html\",\"country\":\"MN\",\"language\":\"tl\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(194, 'default', 'created', 'App\\Models\\UserInfo', 'created', 92, NULL, NULL, '{\"attributes\":{\"id\":92,\"user_id\":92,\"avatar\":null,\"company\":\"Hermiston, Corkery and Cronin\",\"phone\":\"+19525242128\",\"website\":\"http:\\/\\/gottlieb.net\\/aliquid-ullam-enim-debitis-perspiciatis-nesciunt-necessitatibus-enim\",\"country\":\"AO\",\"language\":\"za\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(195, 'default', 'created', 'App\\Models\\UserInfo', 'created', 93, NULL, NULL, '{\"attributes\":{\"id\":93,\"user_id\":93,\"avatar\":null,\"company\":\"Kuvalis, Keeling and Ullrich\",\"phone\":\"402.552.0885\",\"website\":\"http:\\/\\/www.pouros.biz\\/quia-repellendus-dolorem-vitae-deleniti\",\"country\":\"SM\",\"language\":\"sw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(196, 'default', 'created', 'App\\Models\\UserInfo', 'created', 94, NULL, NULL, '{\"attributes\":{\"id\":94,\"user_id\":94,\"avatar\":null,\"company\":\"Schmitt, Towne and Carter\",\"phone\":\"(410) 281-2156\",\"website\":\"http:\\/\\/reynolds.biz\\/commodi-rerum-deleniti-quaerat-aut-laboriosam-quia.html\",\"country\":\"HT\",\"language\":\"kl\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(197, 'default', 'created', 'App\\Models\\UserInfo', 'created', 95, NULL, NULL, '{\"attributes\":{\"id\":95,\"user_id\":95,\"avatar\":null,\"company\":\"Williamson-Hane\",\"phone\":\"1-480-968-3427\",\"website\":\"https:\\/\\/jenkins.net\\/consectetur-quis-quasi-omnis-tenetur-cupiditate-in.html\",\"country\":\"PG\",\"language\":\"mn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(198, 'default', 'created', 'App\\Models\\UserInfo', 'created', 96, NULL, NULL, '{\"attributes\":{\"id\":96,\"user_id\":96,\"avatar\":null,\"company\":\"McGlynn-Donnelly\",\"phone\":\"440.371.9553\",\"website\":\"http:\\/\\/www.crist.biz\\/\",\"country\":\"LC\",\"language\":\"jv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(199, 'default', 'created', 'App\\Models\\UserInfo', 'created', 97, NULL, NULL, '{\"attributes\":{\"id\":97,\"user_id\":97,\"avatar\":null,\"company\":\"Schinner LLC\",\"phone\":\"380.336.9448\",\"website\":\"http:\\/\\/hauck.com\\/non-ratione-sapiente-maiores-aut\",\"country\":\"LI\",\"language\":\"ku\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(200, 'default', 'created', 'App\\Models\\UserInfo', 'created', 98, NULL, NULL, '{\"attributes\":{\"id\":98,\"user_id\":98,\"avatar\":null,\"company\":\"Wilkinson, Nolan and Predovic\",\"phone\":\"+1-743-381-5031\",\"website\":\"http:\\/\\/www.spencer.org\\/expedita-molestiae-nulla-neque-accusantium-illo\",\"country\":\"GE\",\"language\":\"is\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(201, 'default', 'created', 'App\\Models\\UserInfo', 'created', 99, NULL, NULL, '{\"attributes\":{\"id\":99,\"user_id\":99,\"avatar\":null,\"company\":\"Hegmann-Heathcote\",\"phone\":\"865-879-1534\",\"website\":\"http:\\/\\/runolfsson.com\\/maxime-laborum-voluptatum-totam-itaque-aspernatur-sed-illum.html\",\"country\":\"MN\",\"language\":\"gu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(202, 'default', 'created', 'App\\Models\\UserInfo', 'created', 100, NULL, NULL, '{\"attributes\":{\"id\":100,\"user_id\":100,\"avatar\":null,\"company\":\"Block, Daugherty and Lynch\",\"phone\":\"+1.364.915.1307\",\"website\":\"http:\\/\\/www.maggio.com\\/\",\"country\":\"SS\",\"language\":\"so\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(203, 'default', 'created', 'App\\Models\\UserInfo', 'created', 101, NULL, NULL, '{\"attributes\":{\"id\":101,\"user_id\":101,\"avatar\":null,\"company\":\"Blick-Wiza\",\"phone\":\"+15019497591\",\"website\":\"http:\\/\\/www.klocko.net\\/\",\"country\":\"AI\",\"language\":\"oj\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(204, 'default', 'created', 'App\\Models\\UserInfo', 'created', 102, NULL, NULL, '{\"attributes\":{\"id\":102,\"user_id\":102,\"avatar\":null,\"company\":\"Reichert LLC\",\"phone\":\"520-424-4966\",\"website\":\"https:\\/\\/oconnell.com\\/qui-quis-minus-et.html\",\"country\":\"BD\",\"language\":\"lv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(205, 'default', 'created', 'App\\Models\\User', 'created', 103, NULL, NULL, '{\"attributes\":{\"id\":103,\"first_name\":\"MD\",\"last_name\":\"Masum\",\"email\":\"admin@masum.com\",\"email_verified_at\":null,\"password\":\"$2y$10$AfzXoCcyhMGbQnmKfBMOb.XjIEcEmIIFm7oA8T0CXSAKdl7btQwcG\",\"api_token\":null,\"remember_token\":null,\"created_at\":\"2023-01-31T09:59:20.000000Z\",\"updated_at\":\"2023-01-31T09:59:20.000000Z\"}}', NULL, '2023-01-31 03:59:20', '2023-01-31 03:59:20'),
(206, 'default', 'created', 'App\\Models\\UserInfo', 'created', 103, 'App\\Models\\User', 103, '{\"attributes\":{\"id\":103,\"user_id\":103,\"avatar\":\"images\\/m4QvXPPU5YcsWG4EOSVsQRBjI5eMIxL4CblmuFzN.png\",\"company\":null,\"phone\":\"016\",\"website\":null,\"country\":\"BD\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"currency\":null,\"communication\":{\"email\":\"1\",\"phone\":\"0\"},\"marketing\":0,\"created_at\":\"2023-01-31T10:05:00.000000Z\",\"updated_at\":\"2023-01-31T10:05:00.000000Z\"}}', NULL, '2023-01-31 04:05:00', '2023-01-31 04:05:00'),
(207, 'default', 'updated', 'App\\Models\\User', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"first_name\":\"GCL\",\"last_name\":\"Admin\",\"updated_at\":\"2023-01-31T10:56:45.000000Z\"},\"old\":{\"first_name\":\"MD\",\"last_name\":\"Masum\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-31 04:56:45', '2023-01-31 04:56:45'),
(208, 'default', 'updated', 'App\\Models\\UserInfo', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"avatar\":\"images\\/DA6oVfAqPOcSR4AjGbo1TryIlKbWmthbgXhgCtpx.png\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"communication\":{\"email\":\"0\",\"phone\":\"0\"},\"marketing\":0,\"updated_at\":\"2023-01-31T10:56:45.000000Z\"},\"old\":{\"avatar\":null,\"language\":\"ik\",\"timezone\":null,\"communication\":null,\"marketing\":null,\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-31 04:56:45', '2023-01-31 04:56:45'),
(209, 'default', 'created', 'App\\Models\\User', 'created', 104, NULL, NULL, '{\"attributes\":{\"id\":104,\"first_name\":\"Global\",\"last_name\":\"Admin\",\"email\":\"info@gslcorporate.com\",\"email_verified_at\":null,\"password\":\"$2y$10$OaFIVnac4qSoGiPY1ztUf.h7oNyGse1Ub9hGPrFEmVOnJIX5qTVye\",\"api_token\":null,\"remember_token\":null,\"created_at\":\"2023-01-31T12:24:50.000000Z\",\"updated_at\":\"2023-01-31T12:24:50.000000Z\"}}', NULL, '2023-01-31 06:24:50', '2023-01-31 06:24:50'),
(210, 'default', 'created', 'App\\Models\\UserInfo', 'created', 104, 'App\\Models\\User', 104, '{\"attributes\":{\"id\":104,\"user_id\":104,\"avatar\":\"images\\/8qtHFI0f9xOIss1lEz0Z62AGc3q3I5OyPrLF2ONx.png\",\"company\":null,\"phone\":\"01711 531786\",\"website\":null,\"country\":\"BD\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"currency\":null,\"communication\":{\"email\":\"0\",\"phone\":\"0\"},\"marketing\":0,\"created_at\":\"2023-01-31T12:27:48.000000Z\",\"updated_at\":\"2023-01-31T12:27:48.000000Z\"}}', NULL, '2023-01-31 06:27:48', '2023-01-31 06:27:48'),
(1, 'default', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{\"attributes\":{\"id\":1,\"first_name\":\"Hipolito\",\"last_name\":\"Aufderhar\",\"email\":\"demo@demo.com\",\"email_verified_at\":\"2023-01-30T11:05:03.000000Z\",\"password\":\"$2y$10$IjQ\\/OJA1sHLz1szIOR8OEu6q4.LcpSAOtoTmQP50U8NSQm\\/n.Hr8.\",\"api_token\":\"$2y$10$va5qgqH3w32oIcZhjZ0j2e6UKQN8X7mm3qr93iDW0aYBMaTZdtCTC\",\"remember_token\":null,\"created_at\":\"2023-01-30T11:05:03.000000Z\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-30 05:05:03', '2023-01-30 05:05:03'),
(2, 'default', 'created', 'App\\Models\\UserInfo', 'created', 1, NULL, NULL, '{\"attributes\":{\"id\":1,\"user_id\":1,\"avatar\":null,\"company\":\"Bradtke, Schaden and Greenfelder\",\"phone\":\"(443) 823-0276\",\"website\":\"http:\\/\\/www.stracke.org\\/velit-voluptatem-modi-sit-vel-tenetur\",\"country\":\"CK\",\"language\":\"ik\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:03.000000Z\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-30 05:05:03', '2023-01-30 05:05:03'),
(3, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{\"attributes\":{\"id\":2,\"first_name\":\"Easter\",\"last_name\":\"Rath\",\"email\":\"admin@demo.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$1MsuFFDinnOAsROoLuGULukkYyZecQVwndX8I132oq7Ex6PweRotS\",\"api_token\":\"$2y$10$xK9PVEBOAA0wFbQtYv6KougqQQ67xXqYinrAUcwVBJifQud7pGHUa\",\"remember_token\":null,\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(4, 'default', 'created', 'App\\Models\\UserInfo', 'created', 2, NULL, NULL, '{\"attributes\":{\"id\":2,\"user_id\":2,\"avatar\":null,\"company\":\"Herman PLC\",\"phone\":\"404-488-8064\",\"website\":\"http:\\/\\/prohaska.com\\/omnis-tempore-eveniet-possimus-explicabo-totam-qui-dolorum\",\"country\":\"AD\",\"language\":\"cy\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(5, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{\"attributes\":{\"id\":3,\"first_name\":\"Ethan\",\"last_name\":\"Okuneva\",\"email\":\"pmraz@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"mrkayf7HuG\",\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:04', '2023-01-30 05:05:04'),
(6, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{\"attributes\":{\"id\":4,\"first_name\":\"Kaley\",\"last_name\":\"Friesen\",\"email\":\"quincy08@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"TtHHY3OgKO\",\"created_at\":\"2023-01-30T11:05:04.000000Z\",\"updated_at\":\"2023-01-30T11:05:04.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(7, 'default', 'created', 'App\\Models\\User', 'created', 5, NULL, NULL, '{\"attributes\":{\"id\":5,\"first_name\":\"Garret\",\"last_name\":\"Fisher\",\"email\":\"fflatley@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"s3fUCKxKnm\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(8, 'default', 'created', 'App\\Models\\User', 'created', 6, NULL, NULL, '{\"attributes\":{\"id\":6,\"first_name\":\"Hellen\",\"last_name\":\"Smith\",\"email\":\"kellie.bednar@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"LonPVwhUAo\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(9, 'default', 'created', 'App\\Models\\User', 'created', 7, NULL, NULL, '{\"attributes\":{\"id\":7,\"first_name\":\"Malinda\",\"last_name\":\"Hahn\",\"email\":\"yazmin23@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"XjogJZvnUg\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(10, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{\"attributes\":{\"id\":8,\"first_name\":\"Kim\",\"last_name\":\"Quigley\",\"email\":\"ndubuque@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"HwSwupDWEf\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(11, 'default', 'created', 'App\\Models\\User', 'created', 9, NULL, NULL, '{\"attributes\":{\"id\":9,\"first_name\":\"Bernadette\",\"last_name\":\"Ritchie\",\"email\":\"monserrat.schinner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"mmJmSKhUDq\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(12, 'default', 'created', 'App\\Models\\User', 'created', 10, NULL, NULL, '{\"attributes\":{\"id\":10,\"first_name\":\"Rory\",\"last_name\":\"Zulauf\",\"email\":\"rice.tate@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"fiqjbkDGQV\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:05', '2023-01-30 05:05:05'),
(13, 'default', 'created', 'App\\Models\\User', 'created', 11, NULL, NULL, '{\"attributes\":{\"id\":11,\"first_name\":\"Gussie\",\"last_name\":\"Hayes\",\"email\":\"senger.timmy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UtaKOXLr7g\",\"created_at\":\"2023-01-30T11:05:05.000000Z\",\"updated_at\":\"2023-01-30T11:05:05.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(14, 'default', 'created', 'App\\Models\\User', 'created', 12, NULL, NULL, '{\"attributes\":{\"id\":12,\"first_name\":\"Trystan\",\"last_name\":\"Watsica\",\"email\":\"stacy78@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"3D0OK4iSJd\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(15, 'default', 'created', 'App\\Models\\User', 'created', 13, NULL, NULL, '{\"attributes\":{\"id\":13,\"first_name\":\"Zola\",\"last_name\":\"Schumm\",\"email\":\"lily.labadie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"7gbUeuiIEH\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(16, 'default', 'created', 'App\\Models\\User', 'created', 14, NULL, NULL, '{\"attributes\":{\"id\":14,\"first_name\":\"Savannah\",\"last_name\":\"Turner\",\"email\":\"kuhic.jayce@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"vab2Dhe5Va\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(17, 'default', 'created', 'App\\Models\\User', 'created', 15, NULL, NULL, '{\"attributes\":{\"id\":15,\"first_name\":\"Judge\",\"last_name\":\"Crona\",\"email\":\"geoffrey41@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"2vNdlvs79Y\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(18, 'default', 'created', 'App\\Models\\User', 'created', 16, NULL, NULL, '{\"attributes\":{\"id\":16,\"first_name\":\"Angie\",\"last_name\":\"Howell\",\"email\":\"kenny59@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"g5bwoW8BuS\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(19, 'default', 'created', 'App\\Models\\User', 'created', 17, NULL, NULL, '{\"attributes\":{\"id\":17,\"first_name\":\"Elwin\",\"last_name\":\"Hammes\",\"email\":\"alfredo59@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"VhKET9W8EN\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(20, 'default', 'created', 'App\\Models\\User', 'created', 18, NULL, NULL, '{\"attributes\":{\"id\":18,\"first_name\":\"Hailie\",\"last_name\":\"Conn\",\"email\":\"jace.quigley@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xXaP8P73y3\",\"created_at\":\"2023-01-30T11:05:06.000000Z\",\"updated_at\":\"2023-01-30T11:05:06.000000Z\"}}', NULL, '2023-01-30 05:05:06', '2023-01-30 05:05:06'),
(21, 'default', 'created', 'App\\Models\\User', 'created', 19, NULL, NULL, '{\"attributes\":{\"id\":19,\"first_name\":\"Juwan\",\"last_name\":\"Grimes\",\"email\":\"kohler.norris@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Wfq3z3tmye\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(22, 'default', 'created', 'App\\Models\\User', 'created', 20, NULL, NULL, '{\"attributes\":{\"id\":20,\"first_name\":\"Antonetta\",\"last_name\":\"Kirlin\",\"email\":\"harris.rosamond@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"P10us5pot6\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(23, 'default', 'created', 'App\\Models\\User', 'created', 21, NULL, NULL, '{\"attributes\":{\"id\":21,\"first_name\":\"Green\",\"last_name\":\"Rath\",\"email\":\"umckenzie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"gwaUUEQHIv\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(24, 'default', 'created', 'App\\Models\\User', 'created', 22, NULL, NULL, '{\"attributes\":{\"id\":22,\"first_name\":\"Vincenza\",\"last_name\":\"Von\",\"email\":\"violet.larson@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"tKkC19a3zp\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(25, 'default', 'created', 'App\\Models\\User', 'created', 23, NULL, NULL, '{\"attributes\":{\"id\":23,\"first_name\":\"Ladarius\",\"last_name\":\"Schaden\",\"email\":\"ilittel@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"MezWxI1rzd\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(26, 'default', 'created', 'App\\Models\\User', 'created', 24, NULL, NULL, '{\"attributes\":{\"id\":24,\"first_name\":\"Henriette\",\"last_name\":\"Lowe\",\"email\":\"tcollier@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qsC9Tp5NrM\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(27, 'default', 'created', 'App\\Models\\User', 'created', 25, NULL, NULL, '{\"attributes\":{\"id\":25,\"first_name\":\"Carlee\",\"last_name\":\"Turcotte\",\"email\":\"bogisich.pete@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"SczBhHe0nJ\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(28, 'default', 'created', 'App\\Models\\User', 'created', 26, NULL, NULL, '{\"attributes\":{\"id\":26,\"first_name\":\"Felicia\",\"last_name\":\"Sporer\",\"email\":\"eldora13@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rot02Hiukz\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(29, 'default', 'created', 'App\\Models\\User', 'created', 27, NULL, NULL, '{\"attributes\":{\"id\":27,\"first_name\":\"Alanna\",\"last_name\":\"Schaefer\",\"email\":\"derrick.beier@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"yup6EMcuEf\",\"created_at\":\"2023-01-30T11:05:07.000000Z\",\"updated_at\":\"2023-01-30T11:05:07.000000Z\"}}', NULL, '2023-01-30 05:05:07', '2023-01-30 05:05:07'),
(30, 'default', 'created', 'App\\Models\\User', 'created', 28, NULL, NULL, '{\"attributes\":{\"id\":28,\"first_name\":\"Christy\",\"last_name\":\"Runolfsson\",\"email\":\"emanuel.walsh@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"IcgX9O2NVn\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(31, 'default', 'created', 'App\\Models\\User', 'created', 29, NULL, NULL, '{\"attributes\":{\"id\":29,\"first_name\":\"Cecile\",\"last_name\":\"Becker\",\"email\":\"lemke.jadyn@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nTmZ4goJEv\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(32, 'default', 'created', 'App\\Models\\User', 'created', 30, NULL, NULL, '{\"attributes\":{\"id\":30,\"first_name\":\"Evan\",\"last_name\":\"Hudson\",\"email\":\"tthompson@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"SE77STcTKC\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(33, 'default', 'created', 'App\\Models\\User', 'created', 31, NULL, NULL, '{\"attributes\":{\"id\":31,\"first_name\":\"Demarco\",\"last_name\":\"Rath\",\"email\":\"jpfannerstill@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"8NgIfyBQES\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(34, 'default', 'created', 'App\\Models\\User', 'created', 32, NULL, NULL, '{\"attributes\":{\"id\":32,\"first_name\":\"Napoleon\",\"last_name\":\"Pollich\",\"email\":\"vallie.dubuque@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"EZoVRtkUCp\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(35, 'default', 'created', 'App\\Models\\User', 'created', 33, NULL, NULL, '{\"attributes\":{\"id\":33,\"first_name\":\"Hilbert\",\"last_name\":\"Lynch\",\"email\":\"golda93@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"RXAvhRqi4X\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(36, 'default', 'created', 'App\\Models\\User', 'created', 34, NULL, NULL, '{\"attributes\":{\"id\":34,\"first_name\":\"Melany\",\"last_name\":\"Kuvalis\",\"email\":\"krystel25@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qpUFG2DM4p\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(37, 'default', 'created', 'App\\Models\\User', 'created', 35, NULL, NULL, '{\"attributes\":{\"id\":35,\"first_name\":\"Marlee\",\"last_name\":\"Cormier\",\"email\":\"nadia.heller@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"PFEyDUjXgV\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(38, 'default', 'created', 'App\\Models\\User', 'created', 36, NULL, NULL, '{\"attributes\":{\"id\":36,\"first_name\":\"Jayden\",\"last_name\":\"Schoen\",\"email\":\"america.purdy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9poiXp5qbD\",\"created_at\":\"2023-01-30T11:05:08.000000Z\",\"updated_at\":\"2023-01-30T11:05:08.000000Z\"}}', NULL, '2023-01-30 05:05:08', '2023-01-30 05:05:08'),
(39, 'default', 'created', 'App\\Models\\User', 'created', 37, NULL, NULL, '{\"attributes\":{\"id\":37,\"first_name\":\"Alene\",\"last_name\":\"Mueller\",\"email\":\"pshields@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"k11ebpE9oG\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(40, 'default', 'created', 'App\\Models\\User', 'created', 38, NULL, NULL, '{\"attributes\":{\"id\":38,\"first_name\":\"Obie\",\"last_name\":\"Crist\",\"email\":\"dare.queenie@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ysGN2Yg4FW\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(41, 'default', 'created', 'App\\Models\\User', 'created', 39, NULL, NULL, '{\"attributes\":{\"id\":39,\"first_name\":\"Flossie\",\"last_name\":\"Collins\",\"email\":\"huel.grant@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xV3VQ6HkgJ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(42, 'default', 'created', 'App\\Models\\User', 'created', 40, NULL, NULL, '{\"attributes\":{\"id\":40,\"first_name\":\"Chasity\",\"last_name\":\"Ondricka\",\"email\":\"courtney65@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"lYYqHfhOAN\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(43, 'default', 'created', 'App\\Models\\User', 'created', 41, NULL, NULL, '{\"attributes\":{\"id\":41,\"first_name\":\"Gerry\",\"last_name\":\"Cummings\",\"email\":\"jarod.stanton@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"fNB77b2MoO\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(44, 'default', 'created', 'App\\Models\\User', 'created', 42, NULL, NULL, '{\"attributes\":{\"id\":42,\"first_name\":\"Ian\",\"last_name\":\"Walker\",\"email\":\"reichel.ford@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"zq1TGXCRdt\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(45, 'default', 'created', 'App\\Models\\User', 'created', 43, NULL, NULL, '{\"attributes\":{\"id\":43,\"first_name\":\"Moses\",\"last_name\":\"Ritchie\",\"email\":\"wunsch.lynn@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"t9zkKOM4WJ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:09', '2023-01-30 05:05:09'),
(46, 'default', 'created', 'App\\Models\\User', 'created', 44, NULL, NULL, '{\"attributes\":{\"id\":44,\"first_name\":\"Fern\",\"last_name\":\"McDermott\",\"email\":\"murazik.rosemary@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"KrbuSUD1XQ\",\"created_at\":\"2023-01-30T11:05:09.000000Z\",\"updated_at\":\"2023-01-30T11:05:09.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(47, 'default', 'created', 'App\\Models\\User', 'created', 45, NULL, NULL, '{\"attributes\":{\"id\":45,\"first_name\":\"Amani\",\"last_name\":\"West\",\"email\":\"hbernhard@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"1xL6mQN7FO\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(48, 'default', 'created', 'App\\Models\\User', 'created', 46, NULL, NULL, '{\"attributes\":{\"id\":46,\"first_name\":\"Leta\",\"last_name\":\"O\'Reilly\",\"email\":\"leora59@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"TU5fpNB9Nb\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(49, 'default', 'created', 'App\\Models\\User', 'created', 47, NULL, NULL, '{\"attributes\":{\"id\":47,\"first_name\":\"Hilda\",\"last_name\":\"Erdman\",\"email\":\"oswaldo.mann@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xXNp2FmCOo\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(50, 'default', 'created', 'App\\Models\\User', 'created', 48, NULL, NULL, '{\"attributes\":{\"id\":48,\"first_name\":\"Deborah\",\"last_name\":\"Considine\",\"email\":\"auer.stephanie@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"alDAhYCNuu\",\"created_at\":\"2023-01-30T11:05:10.000000Z\",\"updated_at\":\"2023-01-30T11:05:10.000000Z\"}}', NULL, '2023-01-30 05:05:10', '2023-01-30 05:05:10'),
(51, 'default', 'created', 'App\\Models\\User', 'created', 49, NULL, NULL, '{\"attributes\":{\"id\":49,\"first_name\":\"Garnett\",\"last_name\":\"Wiegand\",\"email\":\"bryce.denesik@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"tNNJfEkqMK\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(52, 'default', 'created', 'App\\Models\\User', 'created', 50, NULL, NULL, '{\"attributes\":{\"id\":50,\"first_name\":\"Roscoe\",\"last_name\":\"Feeney\",\"email\":\"wpadberg@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"kpe5IiXSVa\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(53, 'default', 'created', 'App\\Models\\User', 'created', 51, NULL, NULL, '{\"attributes\":{\"id\":51,\"first_name\":\"Naomie\",\"last_name\":\"Watsica\",\"email\":\"tleuschke@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"sWw2mC4a2S\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(54, 'default', 'created', 'App\\Models\\User', 'created', 52, NULL, NULL, '{\"attributes\":{\"id\":52,\"first_name\":\"Mercedes\",\"last_name\":\"Dickinson\",\"email\":\"cory.upton@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qeOebkITj7\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(55, 'default', 'created', 'App\\Models\\User', 'created', 53, NULL, NULL, '{\"attributes\":{\"id\":53,\"first_name\":\"Kieran\",\"last_name\":\"Kemmer\",\"email\":\"andy.cummerata@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"I3drbkDH4I\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(56, 'default', 'created', 'App\\Models\\User', 'created', 54, NULL, NULL, '{\"attributes\":{\"id\":54,\"first_name\":\"Rhea\",\"last_name\":\"Dickinson\",\"email\":\"swaniawski.griffin@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"WH6pX1HwR6\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:11', '2023-01-30 05:05:11'),
(57, 'default', 'created', 'App\\Models\\User', 'created', 55, NULL, NULL, '{\"attributes\":{\"id\":55,\"first_name\":\"Hazel\",\"last_name\":\"Cronin\",\"email\":\"rutherford.kacie@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9ULw9zXY2I\",\"created_at\":\"2023-01-30T11:05:11.000000Z\",\"updated_at\":\"2023-01-30T11:05:11.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(58, 'default', 'created', 'App\\Models\\User', 'created', 56, NULL, NULL, '{\"attributes\":{\"id\":56,\"first_name\":\"Maiya\",\"last_name\":\"Heidenreich\",\"email\":\"thaddeus59@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"krgAteBVIN\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(59, 'default', 'created', 'App\\Models\\User', 'created', 57, NULL, NULL, '{\"attributes\":{\"id\":57,\"first_name\":\"Winnifred\",\"last_name\":\"Bartoletti\",\"email\":\"garry05@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"aIxomeVwSJ\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(60, 'default', 'created', 'App\\Models\\User', 'created', 58, NULL, NULL, '{\"attributes\":{\"id\":58,\"first_name\":\"Sarina\",\"last_name\":\"Herman\",\"email\":\"layne.jacobi@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"3TuPDrdjpi\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(61, 'default', 'created', 'App\\Models\\User', 'created', 59, NULL, NULL, '{\"attributes\":{\"id\":59,\"first_name\":\"Hallie\",\"last_name\":\"Jerde\",\"email\":\"hprohaska@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"S4lM8Z5lhU\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(62, 'default', 'created', 'App\\Models\\User', 'created', 60, NULL, NULL, '{\"attributes\":{\"id\":60,\"first_name\":\"Kailyn\",\"last_name\":\"West\",\"email\":\"mhammes@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nv9ONrYWsc\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:12', '2023-01-30 05:05:12'),
(63, 'default', 'created', 'App\\Models\\User', 'created', 61, NULL, NULL, '{\"attributes\":{\"id\":61,\"first_name\":\"Morris\",\"last_name\":\"Denesik\",\"email\":\"junius01@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"xVm5v9SPeb\",\"created_at\":\"2023-01-30T11:05:12.000000Z\",\"updated_at\":\"2023-01-30T11:05:12.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(64, 'default', 'created', 'App\\Models\\User', 'created', 62, NULL, NULL, '{\"attributes\":{\"id\":62,\"first_name\":\"Cielo\",\"last_name\":\"Smitham\",\"email\":\"derek.mohr@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"OmitnGORr4\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(65, 'default', 'created', 'App\\Models\\User', 'created', 63, NULL, NULL, '{\"attributes\":{\"id\":63,\"first_name\":\"Shirley\",\"last_name\":\"Nader\",\"email\":\"serenity35@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"KEeYzIfshy\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(66, 'default', 'created', 'App\\Models\\User', 'created', 64, NULL, NULL, '{\"attributes\":{\"id\":64,\"first_name\":\"Beverly\",\"last_name\":\"Beatty\",\"email\":\"marguerite.schamberger@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"8gJidekKDF\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(67, 'default', 'created', 'App\\Models\\User', 'created', 65, NULL, NULL, '{\"attributes\":{\"id\":65,\"first_name\":\"Elvera\",\"last_name\":\"Ledner\",\"email\":\"reginald.maggio@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"vxAPW2mHuf\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(68, 'default', 'created', 'App\\Models\\User', 'created', 66, NULL, NULL, '{\"attributes\":{\"id\":66,\"first_name\":\"Shanelle\",\"last_name\":\"Terry\",\"email\":\"elody.oberbrunner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"dd6SGqwrRn\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(69, 'default', 'created', 'App\\Models\\User', 'created', 67, NULL, NULL, '{\"attributes\":{\"id\":67,\"first_name\":\"Pascale\",\"last_name\":\"Wilkinson\",\"email\":\"ford44@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rRypnV43F5\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(70, 'default', 'created', 'App\\Models\\User', 'created', 68, NULL, NULL, '{\"attributes\":{\"id\":68,\"first_name\":\"Destiny\",\"last_name\":\"Stamm\",\"email\":\"evie60@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ShsuLLom2q\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(71, 'default', 'created', 'App\\Models\\User', 'created', 69, NULL, NULL, '{\"attributes\":{\"id\":69,\"first_name\":\"Marilie\",\"last_name\":\"Skiles\",\"email\":\"dibbert.karina@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"GWaQ3tWxfL\",\"created_at\":\"2023-01-30T11:05:13.000000Z\",\"updated_at\":\"2023-01-30T11:05:13.000000Z\"}}', NULL, '2023-01-30 05:05:13', '2023-01-30 05:05:13'),
(72, 'default', 'created', 'App\\Models\\User', 'created', 70, NULL, NULL, '{\"attributes\":{\"id\":70,\"first_name\":\"Theodora\",\"last_name\":\"Beatty\",\"email\":\"kreiger.elissa@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"O3j9EZJlsC\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(73, 'default', 'created', 'App\\Models\\User', 'created', 71, NULL, NULL, '{\"attributes\":{\"id\":71,\"first_name\":\"Isabel\",\"last_name\":\"Gibson\",\"email\":\"ogleichner@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Rm2bgN7hV7\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(74, 'default', 'created', 'App\\Models\\User', 'created', 72, NULL, NULL, '{\"attributes\":{\"id\":72,\"first_name\":\"Mallory\",\"last_name\":\"Heller\",\"email\":\"larkin.stone@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"7k4RknawAm\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(75, 'default', 'created', 'App\\Models\\User', 'created', 73, NULL, NULL, '{\"attributes\":{\"id\":73,\"first_name\":\"Sigmund\",\"last_name\":\"Ortiz\",\"email\":\"roslyn74@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"GCaqhXJbmV\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(76, 'default', 'created', 'App\\Models\\User', 'created', 74, NULL, NULL, '{\"attributes\":{\"id\":74,\"first_name\":\"Genoveva\",\"last_name\":\"Towne\",\"email\":\"alyson35@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"zCb74E1GPV\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(77, 'default', 'created', 'App\\Models\\User', 'created', 75, NULL, NULL, '{\"attributes\":{\"id\":75,\"first_name\":\"Astrid\",\"last_name\":\"Metz\",\"email\":\"theodora.schaefer@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"RPhowGhT6M\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(78, 'default', 'created', 'App\\Models\\User', 'created', 76, NULL, NULL, '{\"attributes\":{\"id\":76,\"first_name\":\"Myah\",\"last_name\":\"Steuber\",\"email\":\"carole89@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"d4CW2Qw5Iw\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(79, 'default', 'created', 'App\\Models\\User', 'created', 77, NULL, NULL, '{\"attributes\":{\"id\":77,\"first_name\":\"Verla\",\"last_name\":\"Gerhold\",\"email\":\"bogisich.nona@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"B5FdbRvX2n\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:14', '2023-01-30 05:05:14'),
(80, 'default', 'created', 'App\\Models\\User', 'created', 78, NULL, NULL, '{\"attributes\":{\"id\":78,\"first_name\":\"Leola\",\"last_name\":\"Toy\",\"email\":\"drew63@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ENgDrPqWPe\",\"created_at\":\"2023-01-30T11:05:14.000000Z\",\"updated_at\":\"2023-01-30T11:05:14.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(81, 'default', 'created', 'App\\Models\\User', 'created', 79, NULL, NULL, '{\"attributes\":{\"id\":79,\"first_name\":\"Myrtle\",\"last_name\":\"Haley\",\"email\":\"ressie44@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"gJed20DG3Z\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(82, 'default', 'created', 'App\\Models\\User', 'created', 80, NULL, NULL, '{\"attributes\":{\"id\":80,\"first_name\":\"Benjamin\",\"last_name\":\"Jenkins\",\"email\":\"caitlyn.harvey@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"rmAN2rO4ym\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(83, 'default', 'created', 'App\\Models\\User', 'created', 81, NULL, NULL, '{\"attributes\":{\"id\":81,\"first_name\":\"Wayne\",\"last_name\":\"Wilderman\",\"email\":\"schuppe.esperanza@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"uLkLMDY4Oq\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(84, 'default', 'created', 'App\\Models\\User', 'created', 82, NULL, NULL, '{\"attributes\":{\"id\":82,\"first_name\":\"Jules\",\"last_name\":\"Keebler\",\"email\":\"mellie18@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"AxZeuFKmfY\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(85, 'default', 'created', 'App\\Models\\User', 'created', 83, NULL, NULL, '{\"attributes\":{\"id\":83,\"first_name\":\"Zaria\",\"last_name\":\"Wisoky\",\"email\":\"monica90@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"n8FYas3Wzp\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(86, 'default', 'created', 'App\\Models\\User', 'created', 84, NULL, NULL, '{\"attributes\":{\"id\":84,\"first_name\":\"Coy\",\"last_name\":\"Mante\",\"email\":\"reynolds.ciara@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Hq7gz56jWg\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(87, 'default', 'created', 'App\\Models\\User', 'created', 85, NULL, NULL, '{\"attributes\":{\"id\":85,\"first_name\":\"Darlene\",\"last_name\":\"Breitenberg\",\"email\":\"gudrun.morissette@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"ofJqanD3yo\",\"created_at\":\"2023-01-30T11:05:15.000000Z\",\"updated_at\":\"2023-01-30T11:05:15.000000Z\"}}', NULL, '2023-01-30 05:05:15', '2023-01-30 05:05:15'),
(88, 'default', 'created', 'App\\Models\\User', 'created', 86, NULL, NULL, '{\"attributes\":{\"id\":86,\"first_name\":\"Lane\",\"last_name\":\"Sawayn\",\"email\":\"zcasper@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Qll9tF02Ye\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(89, 'default', 'created', 'App\\Models\\User', 'created', 87, NULL, NULL, '{\"attributes\":{\"id\":87,\"first_name\":\"Lucile\",\"last_name\":\"Osinski\",\"email\":\"cooper15@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UiuaKq4Mtl\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(90, 'default', 'created', 'App\\Models\\User', 'created', 88, NULL, NULL, '{\"attributes\":{\"id\":88,\"first_name\":\"Claire\",\"last_name\":\"Dooley\",\"email\":\"tianna70@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"nzzmvrqAiH\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(91, 'default', 'created', 'App\\Models\\User', 'created', 89, NULL, NULL, '{\"attributes\":{\"id\":89,\"first_name\":\"Yvette\",\"last_name\":\"Toy\",\"email\":\"marianna.jerde@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"wof9Yp8Wro\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(92, 'default', 'created', 'App\\Models\\User', 'created', 90, NULL, NULL, '{\"attributes\":{\"id\":90,\"first_name\":\"Evalyn\",\"last_name\":\"Abshire\",\"email\":\"chermiston@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"HTV2gdhxMm\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(93, 'default', 'created', 'App\\Models\\User', 'created', 91, NULL, NULL, '{\"attributes\":{\"id\":91,\"first_name\":\"Dasia\",\"last_name\":\"Bergnaum\",\"email\":\"xpaucek@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"9Opm7FMNdg\",\"created_at\":\"2023-01-30T11:05:16.000000Z\",\"updated_at\":\"2023-01-30T11:05:16.000000Z\"}}', NULL, '2023-01-30 05:05:16', '2023-01-30 05:05:16'),
(94, 'default', 'created', 'App\\Models\\User', 'created', 92, NULL, NULL, '{\"attributes\":{\"id\":92,\"first_name\":\"Evangeline\",\"last_name\":\"Conroy\",\"email\":\"makenna53@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"qG7WhhST3M\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(95, 'default', 'created', 'App\\Models\\User', 'created', 93, NULL, NULL, '{\"attributes\":{\"id\":93,\"first_name\":\"Gina\",\"last_name\":\"Hilpert\",\"email\":\"piper55@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"bsVFGZV5zT\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(96, 'default', 'created', 'App\\Models\\User', 'created', 94, NULL, NULL, '{\"attributes\":{\"id\":94,\"first_name\":\"Cristina\",\"last_name\":\"Koss\",\"email\":\"jgrady@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"QOjqDacaIg\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(97, 'default', 'created', 'App\\Models\\User', 'created', 95, NULL, NULL, '{\"attributes\":{\"id\":95,\"first_name\":\"Alfonso\",\"last_name\":\"Smith\",\"email\":\"areichert@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"UUkcj5VYS5\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(98, 'default', 'created', 'App\\Models\\User', 'created', 96, NULL, NULL, '{\"attributes\":{\"id\":96,\"first_name\":\"Hilda\",\"last_name\":\"Crist\",\"email\":\"salma61@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"QKbmp8d3F5\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(99, 'default', 'created', 'App\\Models\\User', 'created', 97, NULL, NULL, '{\"attributes\":{\"id\":97,\"first_name\":\"Cindy\",\"last_name\":\"Bauch\",\"email\":\"schaefer.fernando@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"Ip4gTYFvXf\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(100, 'default', 'created', 'App\\Models\\User', 'created', 98, NULL, NULL, '{\"attributes\":{\"id\":98,\"first_name\":\"Van\",\"last_name\":\"Bogan\",\"email\":\"jacobson.adolfo@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"yjJUSXrakq\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(101, 'default', 'created', 'App\\Models\\User', 'created', 99, NULL, NULL, '{\"attributes\":{\"id\":99,\"first_name\":\"Magnolia\",\"last_name\":\"Donnelly\",\"email\":\"vidal.hodkiewicz@example.net\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"eeatBeIL6b\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(102, 'default', 'created', 'App\\Models\\User', 'created', 100, NULL, NULL, '{\"attributes\":{\"id\":100,\"first_name\":\"Mozelle\",\"last_name\":\"Kutch\",\"email\":\"rconroy@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"4aGgDbTzBK\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(103, 'default', 'created', 'App\\Models\\User', 'created', 101, NULL, NULL, '{\"attributes\":{\"id\":101,\"first_name\":\"Ashlynn\",\"last_name\":\"Treutel\",\"email\":\"schinner.thaddeus@example.org\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"IdQEP9xmzy\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:17', '2023-01-30 05:05:17'),
(104, 'default', 'created', 'App\\Models\\User', 'created', 102, NULL, NULL, '{\"attributes\":{\"id\":102,\"first_name\":\"Mozell\",\"last_name\":\"Ruecker\",\"email\":\"zschmidt@example.com\",\"email_verified_at\":\"2023-01-30T11:05:04.000000Z\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"api_token\":null,\"remember_token\":\"h6zfzsNxNy\",\"created_at\":\"2023-01-30T11:05:17.000000Z\",\"updated_at\":\"2023-01-30T11:05:17.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(105, 'default', 'created', 'App\\Models\\UserInfo', 'created', 3, NULL, NULL, '{\"attributes\":{\"id\":3,\"user_id\":3,\"avatar\":null,\"company\":\"Halvorson Group\",\"phone\":\"+1 (430) 280-0462\",\"website\":\"http:\\/\\/www.zieme.info\\/veritatis-id-molestiae-ut-atque.html\",\"country\":\"BA\",\"language\":\"be\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(106, 'default', 'created', 'App\\Models\\UserInfo', 'created', 4, NULL, NULL, '{\"attributes\":{\"id\":4,\"user_id\":4,\"avatar\":null,\"company\":\"Von LLC\",\"phone\":\"845.285.4860\",\"website\":\"https:\\/\\/www.zboncak.com\\/reprehenderit-et-et-dolore-adipisci\",\"country\":\"IT\",\"language\":\"el\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(107, 'default', 'created', 'App\\Models\\UserInfo', 'created', 5, NULL, NULL, '{\"attributes\":{\"id\":5,\"user_id\":5,\"avatar\":null,\"company\":\"Upton-Stamm\",\"phone\":\"1-561-410-5112\",\"website\":\"http:\\/\\/www.marquardt.info\\/voluptatem-consequatur-repellat-aperiam-ipsam\",\"country\":\"RE\",\"language\":\"ho\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(108, 'default', 'created', 'App\\Models\\UserInfo', 'created', 6, NULL, NULL, '{\"attributes\":{\"id\":6,\"user_id\":6,\"avatar\":null,\"company\":\"Flatley, Howell and Lubowitz\",\"phone\":\"1-865-480-6435\",\"website\":\"https:\\/\\/deckow.com\\/aliquam-in-ut-autem-esse-voluptas-facilis.html\",\"country\":\"HT\",\"language\":\"ps\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(109, 'default', 'created', 'App\\Models\\UserInfo', 'created', 7, NULL, NULL, '{\"attributes\":{\"id\":7,\"user_id\":7,\"avatar\":null,\"company\":\"Watsica, Gutkowski and Blanda\",\"phone\":\"+1-445-910-2778\",\"website\":\"http:\\/\\/marquardt.com\\/\",\"country\":\"CF\",\"language\":\"ab\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(110, 'default', 'created', 'App\\Models\\UserInfo', 'created', 8, NULL, NULL, '{\"attributes\":{\"id\":8,\"user_id\":8,\"avatar\":null,\"company\":\"Deckow Ltd\",\"phone\":\"434.253.5867\",\"website\":\"http:\\/\\/www.balistreri.com\\/rem-exercitationem-illo-facilis-sunt.html\",\"country\":\"BN\",\"language\":\"iu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(111, 'default', 'created', 'App\\Models\\UserInfo', 'created', 9, NULL, NULL, '{\"attributes\":{\"id\":9,\"user_id\":9,\"avatar\":null,\"company\":\"Fisher, Mertz and Collins\",\"phone\":\"1-863-860-2159\",\"website\":\"https:\\/\\/www.hayes.com\\/placeat-quia-molestias-et-et-optio-minima-mollitia-excepturi\",\"country\":\"TT\",\"language\":\"ig\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(112, 'default', 'created', 'App\\Models\\UserInfo', 'created', 10, NULL, NULL, '{\"attributes\":{\"id\":10,\"user_id\":10,\"avatar\":null,\"company\":\"Bartoletti Group\",\"phone\":\"+1-248-350-9269\",\"website\":\"http:\\/\\/gottlieb.com\\/non-accusamus-non-aut-dolores-aliquid-incidunt\",\"country\":\"CI\",\"language\":\"li\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:18.000000Z\",\"updated_at\":\"2023-01-30T11:05:18.000000Z\"}}', NULL, '2023-01-30 05:05:18', '2023-01-30 05:05:18'),
(113, 'default', 'created', 'App\\Models\\UserInfo', 'created', 11, NULL, NULL, '{\"attributes\":{\"id\":11,\"user_id\":11,\"avatar\":null,\"company\":\"Dickens-Parker\",\"phone\":\"+1.281.354.0408\",\"website\":\"http:\\/\\/kris.com\\/\",\"country\":\"BB\",\"language\":\"mg\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(114, 'default', 'created', 'App\\Models\\UserInfo', 'created', 12, NULL, NULL, '{\"attributes\":{\"id\":12,\"user_id\":12,\"avatar\":null,\"company\":\"Homenick Inc\",\"phone\":\"+1 (320) 544-0921\",\"website\":\"http:\\/\\/www.lang.com\\/omnis-temporibus-dignissimos-delectus-delectus-ipsam-omnis-iusto-quos\",\"country\":\"HR\",\"language\":\"tr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(115, 'default', 'created', 'App\\Models\\UserInfo', 'created', 13, NULL, NULL, '{\"attributes\":{\"id\":13,\"user_id\":13,\"avatar\":null,\"company\":\"Lemke LLC\",\"phone\":\"980-678-7084\",\"website\":\"http:\\/\\/www.rogahn.org\\/\",\"country\":\"KI\",\"language\":\"az\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(116, 'default', 'created', 'App\\Models\\UserInfo', 'created', 14, NULL, NULL, '{\"attributes\":{\"id\":14,\"user_id\":14,\"avatar\":null,\"company\":\"Braun Ltd\",\"phone\":\"1-629-228-6993\",\"website\":\"http:\\/\\/donnelly.com\\/\",\"country\":\"TZ\",\"language\":\"tt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(117, 'default', 'created', 'App\\Models\\UserInfo', 'created', 15, NULL, NULL, '{\"attributes\":{\"id\":15,\"user_id\":15,\"avatar\":null,\"company\":\"Reichel PLC\",\"phone\":\"1-769-782-1600\",\"website\":\"http:\\/\\/purdy.com\\/ullam-dolor-magni-tempora-eos\",\"country\":\"JP\",\"language\":\"sa\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(118, 'default', 'created', 'App\\Models\\UserInfo', 'created', 16, NULL, NULL, '{\"attributes\":{\"id\":16,\"user_id\":16,\"avatar\":null,\"company\":\"Windler and Sons\",\"phone\":\"239-860-4177\",\"website\":\"https:\\/\\/schumm.net\\/odit-quo-omnis-qui-beatae-saepe-voluptate.html\",\"country\":\"TH\",\"language\":\"na\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(119, 'default', 'created', 'App\\Models\\UserInfo', 'created', 17, NULL, NULL, '{\"attributes\":{\"id\":17,\"user_id\":17,\"avatar\":null,\"company\":\"Lowe, Vandervort and Feest\",\"phone\":\"707.763.6241\",\"website\":\"https:\\/\\/www.dach.biz\\/quisquam-placeat-expedita-quia\",\"country\":\"OM\",\"language\":\"nn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:19.000000Z\",\"updated_at\":\"2023-01-30T11:05:19.000000Z\"}}', NULL, '2023-01-30 05:05:19', '2023-01-30 05:05:19'),
(120, 'default', 'created', 'App\\Models\\UserInfo', 'created', 18, NULL, NULL, '{\"attributes\":{\"id\":18,\"user_id\":18,\"avatar\":null,\"company\":\"Glover-Schneider\",\"phone\":\"1-979-819-8495\",\"website\":\"http:\\/\\/dare.org\\/\",\"country\":\"LC\",\"language\":\"an\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(121, 'default', 'created', 'App\\Models\\UserInfo', 'created', 19, NULL, NULL, '{\"attributes\":{\"id\":19,\"user_id\":19,\"avatar\":null,\"company\":\"Mohr-Gulgowski\",\"phone\":\"850.235.1441\",\"website\":\"https:\\/\\/cassin.biz\\/atque-rerum-soluta-facere.html\",\"country\":\"IL\",\"language\":\"sa\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(122, 'default', 'created', 'App\\Models\\UserInfo', 'created', 20, NULL, NULL, '{\"attributes\":{\"id\":20,\"user_id\":20,\"avatar\":null,\"company\":\"Schaefer, Turcotte and Quitzon\",\"phone\":\"208-900-5173\",\"website\":\"http:\\/\\/www.herman.info\\/\",\"country\":\"BI\",\"language\":\"fo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(123, 'default', 'created', 'App\\Models\\UserInfo', 'created', 21, NULL, NULL, '{\"attributes\":{\"id\":21,\"user_id\":21,\"avatar\":null,\"company\":\"Spinka-Hahn\",\"phone\":\"+1-512-875-0637\",\"website\":\"https:\\/\\/johns.org\\/perspiciatis-doloribus-ut-voluptas-omnis.html\",\"country\":\"DM\",\"language\":\"co\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(124, 'default', 'created', 'App\\Models\\UserInfo', 'created', 22, NULL, NULL, '{\"attributes\":{\"id\":22,\"user_id\":22,\"avatar\":null,\"company\":\"Boehm, Raynor and Cruickshank\",\"phone\":\"+16468822256\",\"website\":\"http:\\/\\/krajcik.info\\/dolores-nam-nulla-aperiam-eius\",\"country\":\"RU\",\"language\":\"lu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(125, 'default', 'created', 'App\\Models\\UserInfo', 'created', 23, NULL, NULL, '{\"attributes\":{\"id\":23,\"user_id\":23,\"avatar\":null,\"company\":\"Morar-Champlin\",\"phone\":\"+1-864-476-3168\",\"website\":\"https:\\/\\/www.schuster.com\\/voluptatem-ducimus-facere-cum-odit\",\"country\":\"CL\",\"language\":\"ig\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(126, 'default', 'created', 'App\\Models\\UserInfo', 'created', 24, NULL, NULL, '{\"attributes\":{\"id\":24,\"user_id\":24,\"avatar\":null,\"company\":\"Torp-Altenwerth\",\"phone\":\"+1-574-241-0944\",\"website\":\"https:\\/\\/goldner.com\\/et-aut-est-eius-a-reprehenderit-quod-quia.html\",\"country\":\"UM\",\"language\":\"ch\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:20.000000Z\",\"updated_at\":\"2023-01-30T11:05:20.000000Z\"}}', NULL, '2023-01-30 05:05:20', '2023-01-30 05:05:20'),
(127, 'default', 'created', 'App\\Models\\UserInfo', 'created', 25, NULL, NULL, '{\"attributes\":{\"id\":25,\"user_id\":25,\"avatar\":null,\"company\":\"Harber-Renner\",\"phone\":\"812.284.0837\",\"website\":\"https:\\/\\/gerhold.com\\/sunt-ab-quidem-sunt-incidunt-quidem-sunt.html\",\"country\":\"LS\",\"language\":\"pi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(128, 'default', 'created', 'App\\Models\\UserInfo', 'created', 26, NULL, NULL, '{\"attributes\":{\"id\":26,\"user_id\":26,\"avatar\":null,\"company\":\"Schinner PLC\",\"phone\":\"508.672.3563\",\"website\":\"https:\\/\\/oberbrunner.com\\/est-quaerat-nam-quia-non.html\",\"country\":\"MV\",\"language\":\"mt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(129, 'default', 'created', 'App\\Models\\UserInfo', 'created', 27, NULL, NULL, '{\"attributes\":{\"id\":27,\"user_id\":27,\"avatar\":null,\"company\":\"Dibbert PLC\",\"phone\":\"386.765.2103\",\"website\":\"https:\\/\\/www.gislason.org\\/reiciendis-in-vero-suscipit-dolore-aspernatur-fugit-et\",\"country\":\"SI\",\"language\":\"mi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(130, 'default', 'created', 'App\\Models\\UserInfo', 'created', 28, NULL, NULL, '{\"attributes\":{\"id\":28,\"user_id\":28,\"avatar\":null,\"company\":\"Watsica, Maggio and Christiansen\",\"phone\":\"(845) 831-9784\",\"website\":\"http:\\/\\/www.gibson.org\\/rerum-distinctio-vel-hic\",\"country\":\"IM\",\"language\":\"nr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(131, 'default', 'created', 'App\\Models\\UserInfo', 'created', 29, NULL, NULL, '{\"attributes\":{\"id\":29,\"user_id\":29,\"avatar\":null,\"company\":\"Boyer PLC\",\"phone\":\"458-508-8836\",\"website\":\"http:\\/\\/www.runolfsson.com\\/voluptate-temporibus-minima-quia-reiciendis\",\"country\":\"VI\",\"language\":\"si\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(132, 'default', 'created', 'App\\Models\\UserInfo', 'created', 30, NULL, NULL, '{\"attributes\":{\"id\":30,\"user_id\":30,\"avatar\":null,\"company\":\"Lehner PLC\",\"phone\":\"+1-341-990-7613\",\"website\":\"http:\\/\\/www.berge.com\\/architecto-vel-rerum-fuga-iste-sunt-aliquid.html\",\"country\":\"MV\",\"language\":\"lt\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(133, 'default', 'created', 'App\\Models\\UserInfo', 'created', 31, NULL, NULL, '{\"attributes\":{\"id\":31,\"user_id\":31,\"avatar\":null,\"company\":\"Cormier and Sons\",\"phone\":\"352.465.0917\",\"website\":\"http:\\/\\/www.kuhlman.com\\/saepe-doloribus-est-possimus-aut-ex-repellat\",\"country\":\"PA\",\"language\":\"ak\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(134, 'default', 'created', 'App\\Models\\UserInfo', 'created', 32, NULL, NULL, '{\"attributes\":{\"id\":32,\"user_id\":32,\"avatar\":null,\"company\":\"Stehr-Moen\",\"phone\":\"701.694.1027\",\"website\":\"http:\\/\\/www.gutmann.info\\/\",\"country\":\"QA\",\"language\":\"pi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:21', '2023-01-30 05:05:21'),
(135, 'default', 'created', 'App\\Models\\UserInfo', 'created', 33, NULL, NULL, '{\"attributes\":{\"id\":33,\"user_id\":33,\"avatar\":null,\"company\":\"Wunsch, Lesch and Little\",\"phone\":\"628-551-5050\",\"website\":\"http:\\/\\/www.moen.biz\\/impedit-accusantium-dolorem-qui-ea-vero-sint-blanditiis\",\"country\":\"TM\",\"language\":\"ps\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:21.000000Z\",\"updated_at\":\"2023-01-30T11:05:21.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(136, 'default', 'created', 'App\\Models\\UserInfo', 'created', 34, NULL, NULL, '{\"attributes\":{\"id\":34,\"user_id\":34,\"avatar\":null,\"company\":\"McClure, Braun and Bashirian\",\"phone\":\"562-269-7745\",\"website\":\"https:\\/\\/crist.com\\/molestiae-laudantium-aliquam-est-sint.html\",\"country\":\"NI\",\"language\":\"or\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(137, 'default', 'created', 'App\\Models\\UserInfo', 'created', 35, NULL, NULL, '{\"attributes\":{\"id\":35,\"user_id\":35,\"avatar\":null,\"company\":\"Bergstrom Group\",\"phone\":\"+13808162577\",\"website\":\"http:\\/\\/www.mayert.biz\\/earum-et-tempore-quae\",\"country\":\"CZ\",\"language\":\"lv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(138, 'default', 'created', 'App\\Models\\UserInfo', 'created', 36, NULL, NULL, '{\"attributes\":{\"id\":36,\"user_id\":36,\"avatar\":null,\"company\":\"Langworth Ltd\",\"phone\":\"(725) 868-5735\",\"website\":\"http:\\/\\/crooks.biz\\/numquam-laborum-sequi-pariatur-ut-omnis\",\"country\":\"DJ\",\"language\":\"ik\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(139, 'default', 'created', 'App\\Models\\UserInfo', 'created', 37, NULL, NULL, '{\"attributes\":{\"id\":37,\"user_id\":37,\"avatar\":null,\"company\":\"Rath-Kassulke\",\"phone\":\"+13079491139\",\"website\":\"http:\\/\\/www.bartoletti.com\\/facilis-deserunt-molestiae-velit-non.html\",\"country\":\"DK\",\"language\":\"ks\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(140, 'default', 'created', 'App\\Models\\UserInfo', 'created', 38, NULL, NULL, '{\"attributes\":{\"id\":38,\"user_id\":38,\"avatar\":null,\"company\":\"Wiza LLC\",\"phone\":\"725-447-2591\",\"website\":\"http:\\/\\/www.kreiger.com\\/eos-est-illo-nulla-itaque-necessitatibus-repudiandae-officiis.html\",\"country\":\"EC\",\"language\":\"qu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(141, 'default', 'created', 'App\\Models\\UserInfo', 'created', 39, NULL, NULL, '{\"attributes\":{\"id\":39,\"user_id\":39,\"avatar\":null,\"company\":\"Durgan Ltd\",\"phone\":\"223-405-5697\",\"website\":\"http:\\/\\/lang.com\\/sed-omnis-quo-ex-qui\",\"country\":\"MQ\",\"language\":\"ky\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(142, 'default', 'created', 'App\\Models\\UserInfo', 'created', 40, NULL, NULL, '{\"attributes\":{\"id\":40,\"user_id\":40,\"avatar\":null,\"company\":\"Gutkowski Inc\",\"phone\":\"985-941-7339\",\"website\":\"https:\\/\\/www.vandervort.biz\\/sapiente-quo-sapiente-vero-vel-rerum\",\"country\":\"GE\",\"language\":\"it\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:22.000000Z\",\"updated_at\":\"2023-01-30T11:05:22.000000Z\"}}', NULL, '2023-01-30 05:05:22', '2023-01-30 05:05:22'),
(143, 'default', 'created', 'App\\Models\\UserInfo', 'created', 41, NULL, NULL, '{\"attributes\":{\"id\":41,\"user_id\":41,\"avatar\":null,\"company\":\"Price PLC\",\"phone\":\"219-808-7763\",\"website\":\"http:\\/\\/carroll.org\\/omnis-provident-autem-et-laudantium-sunt\",\"country\":\"CY\",\"language\":\"ss\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(144, 'default', 'created', 'App\\Models\\UserInfo', 'created', 42, NULL, NULL, '{\"attributes\":{\"id\":42,\"user_id\":42,\"avatar\":null,\"company\":\"Labadie-Hodkiewicz\",\"phone\":\"351.465.2959\",\"website\":\"https:\\/\\/www.grimes.com\\/ducimus-officia-vero-error-ipsam\",\"country\":\"UZ\",\"language\":\"en\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(145, 'default', 'created', 'App\\Models\\UserInfo', 'created', 43, NULL, NULL, '{\"attributes\":{\"id\":43,\"user_id\":43,\"avatar\":null,\"company\":\"Grimes-Greenholt\",\"phone\":\"+1 (351) 844-8219\",\"website\":\"http:\\/\\/www.rohan.com\\/\",\"country\":\"CA\",\"language\":\"ne\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(146, 'default', 'created', 'App\\Models\\UserInfo', 'created', 44, NULL, NULL, '{\"attributes\":{\"id\":44,\"user_id\":44,\"avatar\":null,\"company\":\"Hartmann, Crooks and Hodkiewicz\",\"phone\":\"+16572310289\",\"website\":\"http:\\/\\/spencer.com\\/qui-eveniet-fuga-velit-quae-occaecati-distinctio-voluptatem\",\"country\":\"EE\",\"language\":\"cs\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(147, 'default', 'created', 'App\\Models\\UserInfo', 'created', 45, NULL, NULL, '{\"attributes\":{\"id\":45,\"user_id\":45,\"avatar\":null,\"company\":\"Deckow, Mueller and Turner\",\"phone\":\"1-602-614-7496\",\"website\":\"http:\\/\\/www.hintz.com\\/omnis-qui-ab-qui-quibusdam-vero-itaque-alias.html\",\"country\":\"ZA\",\"language\":\"su\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(148, 'default', 'created', 'App\\Models\\UserInfo', 'created', 46, NULL, NULL, '{\"attributes\":{\"id\":46,\"user_id\":46,\"avatar\":null,\"company\":\"Brown LLC\",\"phone\":\"706.873.7849\",\"website\":\"https:\\/\\/www.metz.com\\/quibusdam-numquam-voluptatem-rem-maxime-quos-sed-fugit-autem\",\"country\":\"BZ\",\"language\":\"se\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(149, 'default', 'created', 'App\\Models\\UserInfo', 'created', 47, NULL, NULL, '{\"attributes\":{\"id\":47,\"user_id\":47,\"avatar\":null,\"company\":\"Predovic-Tillman\",\"phone\":\"+1.469.318.8238\",\"website\":\"https:\\/\\/kautzer.com\\/laboriosam-rem-inventore-in-iusto-voluptatem.html\",\"country\":\"LI\",\"language\":\"cv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:23', '2023-01-30 05:05:23'),
(150, 'default', 'created', 'App\\Models\\UserInfo', 'created', 48, NULL, NULL, '{\"attributes\":{\"id\":48,\"user_id\":48,\"avatar\":null,\"company\":\"Balistreri-Stanton\",\"phone\":\"(858) 826-4734\",\"website\":\"http:\\/\\/www.kautzer.org\\/officia-sint-nesciunt-et-omnis-exercitationem-quis-perspiciatis-voluptatibus.html\",\"country\":\"GE\",\"language\":\"te\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:23.000000Z\",\"updated_at\":\"2023-01-30T11:05:23.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(151, 'default', 'created', 'App\\Models\\UserInfo', 'created', 49, NULL, NULL, '{\"attributes\":{\"id\":49,\"user_id\":49,\"avatar\":null,\"company\":\"Nicolas-McLaughlin\",\"phone\":\"1-937-565-7128\",\"website\":\"https:\\/\\/kreiger.com\\/dolor-nulla-nesciunt-quis-tempora-necessitatibus-enim-qui.html\",\"country\":\"SI\",\"language\":\"st\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(152, 'default', 'created', 'App\\Models\\UserInfo', 'created', 50, NULL, NULL, '{\"attributes\":{\"id\":50,\"user_id\":50,\"avatar\":null,\"company\":\"Hodkiewicz-Rice\",\"phone\":\"1-870-742-1873\",\"website\":\"http:\\/\\/www.vonrueden.com\\/praesentium-sit-ut-voluptatum-quae\",\"country\":\"JP\",\"language\":\"to\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(153, 'default', 'created', 'App\\Models\\UserInfo', 'created', 51, NULL, NULL, '{\"attributes\":{\"id\":51,\"user_id\":51,\"avatar\":null,\"company\":\"Rodriguez-Robel\",\"phone\":\"+1 (860) 497-6952\",\"website\":\"https:\\/\\/www.cartwright.com\\/necessitatibus-recusandae-voluptas-omnis-consectetur-voluptatem-ducimus-laudantium\",\"country\":\"CK\",\"language\":\"ff\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(154, 'default', 'created', 'App\\Models\\UserInfo', 'created', 52, NULL, NULL, '{\"attributes\":{\"id\":52,\"user_id\":52,\"avatar\":null,\"company\":\"Wisozk, O\'Hara and Hermann\",\"phone\":\"757.557.0993\",\"website\":\"http:\\/\\/www.shanahan.net\\/quam-nihil-qui-illum-quis\",\"country\":\"FR\",\"language\":\"fi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(155, 'default', 'created', 'App\\Models\\UserInfo', 'created', 53, NULL, NULL, '{\"attributes\":{\"id\":53,\"user_id\":53,\"avatar\":null,\"company\":\"Lubowitz-Swift\",\"phone\":\"+1 (443) 268-9700\",\"website\":\"https:\\/\\/ortiz.biz\\/molestiae-fugit-est-alias-quisquam-fugiat-magnam-rerum.html\",\"country\":\"NE\",\"language\":\"kw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(156, 'default', 'created', 'App\\Models\\UserInfo', 'created', 54, NULL, NULL, '{\"attributes\":{\"id\":54,\"user_id\":54,\"avatar\":null,\"company\":\"Ritchie-Schneider\",\"phone\":\"+18708464490\",\"website\":\"https:\\/\\/www.veum.net\\/consequatur-et-et-placeat-a\",\"country\":\"CR\",\"language\":\"ht\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(157, 'default', 'created', 'App\\Models\\UserInfo', 'created', 55, NULL, NULL, '{\"attributes\":{\"id\":55,\"user_id\":55,\"avatar\":null,\"company\":\"Franecki-Littel\",\"phone\":\"+1-540-326-5204\",\"website\":\"http:\\/\\/www.weissnat.com\\/eos-numquam-voluptatem-sint-voluptas-enim\",\"country\":\"AR\",\"language\":\"sr\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(158, 'default', 'created', 'App\\Models\\UserInfo', 'created', 56, NULL, NULL, '{\"attributes\":{\"id\":56,\"user_id\":56,\"avatar\":null,\"company\":\"Altenwerth, Carter and Roob\",\"phone\":\"708-355-9725\",\"website\":\"http:\\/\\/hayes.org\\/\",\"country\":\"IL\",\"language\":\"gd\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:24', '2023-01-30 05:05:24'),
(159, 'default', 'created', 'App\\Models\\UserInfo', 'created', 57, NULL, NULL, '{\"attributes\":{\"id\":57,\"user_id\":57,\"avatar\":null,\"company\":\"Keeling Ltd\",\"phone\":\"(657) 242-8735\",\"website\":\"http:\\/\\/fritsch.net\\/\",\"country\":\"PY\",\"language\":\"hz\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:24.000000Z\",\"updated_at\":\"2023-01-30T11:05:24.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(160, 'default', 'created', 'App\\Models\\UserInfo', 'created', 58, NULL, NULL, '{\"attributes\":{\"id\":58,\"user_id\":58,\"avatar\":null,\"company\":\"Dare, Greenfelder and Cartwright\",\"phone\":\"+1.253.351.1265\",\"website\":\"http:\\/\\/www.dooley.com\\/facilis-et-aut-id\",\"country\":\"EC\",\"language\":\"cu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(161, 'default', 'created', 'App\\Models\\UserInfo', 'created', 59, NULL, NULL, '{\"attributes\":{\"id\":59,\"user_id\":59,\"avatar\":null,\"company\":\"Willms-Konopelski\",\"phone\":\"1-559-743-4122\",\"website\":\"https:\\/\\/swaniawski.com\\/voluptas-doloribus-voluptas-sit-ullam.html\",\"country\":\"JP\",\"language\":\"ur\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(162, 'default', 'created', 'App\\Models\\UserInfo', 'created', 60, NULL, NULL, '{\"attributes\":{\"id\":60,\"user_id\":60,\"avatar\":null,\"company\":\"Huel-Daugherty\",\"phone\":\"(425) 393-5322\",\"website\":\"http:\\/\\/beahan.com\\/\",\"country\":\"CA\",\"language\":\"fy\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(163, 'default', 'created', 'App\\Models\\UserInfo', 'created', 61, NULL, NULL, '{\"attributes\":{\"id\":61,\"user_id\":61,\"avatar\":null,\"company\":\"Treutel Ltd\",\"phone\":\"+1-779-492-7096\",\"website\":\"https:\\/\\/www.dubuque.com\\/earum-asperiores-aut-sed-laboriosam\",\"country\":\"NI\",\"language\":\"gn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(164, 'default', 'created', 'App\\Models\\UserInfo', 'created', 62, NULL, NULL, '{\"attributes\":{\"id\":62,\"user_id\":62,\"avatar\":null,\"company\":\"Cremin PLC\",\"phone\":\"986-589-2196\",\"website\":\"http:\\/\\/stanton.com\\/non-possimus-quam-harum-aut-a-similique\",\"country\":\"EG\",\"language\":\"ko\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(165, 'default', 'created', 'App\\Models\\UserInfo', 'created', 63, NULL, NULL, '{\"attributes\":{\"id\":63,\"user_id\":63,\"avatar\":null,\"company\":\"O\'Keefe, Gaylord and Stiedemann\",\"phone\":\"1-501-725-7187\",\"website\":\"http:\\/\\/www.medhurst.net\\/\",\"country\":\"BJ\",\"language\":\"hz\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:25', '2023-01-30 05:05:25'),
(166, 'default', 'created', 'App\\Models\\UserInfo', 'created', 64, NULL, NULL, '{\"attributes\":{\"id\":64,\"user_id\":64,\"avatar\":null,\"company\":\"Rohan-Runolfsdottir\",\"phone\":\"+1.818.467.6683\",\"website\":\"http:\\/\\/jerde.com\\/officiis-nemo-dolorem-excepturi\",\"country\":\"FM\",\"language\":\"af\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:25.000000Z\",\"updated_at\":\"2023-01-30T11:05:25.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(167, 'default', 'created', 'App\\Models\\UserInfo', 'created', 65, NULL, NULL, '{\"attributes\":{\"id\":65,\"user_id\":65,\"avatar\":null,\"company\":\"Wolf LLC\",\"phone\":\"+1-321-875-1048\",\"website\":\"http:\\/\\/lynch.com\\/consectetur-odio-sint-cumque-eaque.html\",\"country\":\"ME\",\"language\":\"sw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(168, 'default', 'created', 'App\\Models\\UserInfo', 'created', 66, NULL, NULL, '{\"attributes\":{\"id\":66,\"user_id\":66,\"avatar\":null,\"company\":\"Kling PLC\",\"phone\":\"909-869-2330\",\"website\":\"http:\\/\\/schmeler.com\\/assumenda-nam-aspernatur-enim-est-delectus-sed-voluptatem\",\"country\":\"KI\",\"language\":\"ie\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(169, 'default', 'created', 'App\\Models\\UserInfo', 'created', 67, NULL, NULL, '{\"attributes\":{\"id\":67,\"user_id\":67,\"avatar\":null,\"company\":\"Haley, Osinski and Von\",\"phone\":\"1-602-210-8939\",\"website\":\"http:\\/\\/langosh.org\\/corporis-qui-exercitationem-quod-fugiat-laudantium-reprehenderit.html\",\"country\":\"ER\",\"language\":\"vi\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(170, 'default', 'created', 'App\\Models\\UserInfo', 'created', 68, NULL, NULL, '{\"attributes\":{\"id\":68,\"user_id\":68,\"avatar\":null,\"company\":\"Fisher PLC\",\"phone\":\"239.313.0694\",\"website\":\"http:\\/\\/zulauf.com\\/ea-dolorum-sint-similique\",\"country\":\"KI\",\"language\":\"be\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(171, 'default', 'created', 'App\\Models\\UserInfo', 'created', 69, NULL, NULL, '{\"attributes\":{\"id\":69,\"user_id\":69,\"avatar\":null,\"company\":\"Bosco LLC\",\"phone\":\"+16783570770\",\"website\":\"http:\\/\\/www.reilly.com\\/sequi-non-sint-non-natus\",\"country\":\"ML\",\"language\":\"kk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(172, 'default', 'created', 'App\\Models\\UserInfo', 'created', 70, NULL, NULL, '{\"attributes\":{\"id\":70,\"user_id\":70,\"avatar\":null,\"company\":\"Torp LLC\",\"phone\":\"1-434-816-4673\",\"website\":\"http:\\/\\/www.skiles.com\\/modi-recusandae-repellat-beatae-quos-suscipit-fugit-exercitationem-necessitatibus.html\",\"country\":\"HT\",\"language\":\"id\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(173, 'default', 'created', 'App\\Models\\UserInfo', 'created', 71, NULL, NULL, '{\"attributes\":{\"id\":71,\"user_id\":71,\"avatar\":null,\"company\":\"Runolfsson Ltd\",\"phone\":\"(283) 709-1775\",\"website\":\"https:\\/\\/hand.com\\/reprehenderit-aut-magni-et-ut-ut-vel.html\",\"country\":\"GD\",\"language\":\"nd\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:26', '2023-01-30 05:05:26'),
(174, 'default', 'created', 'App\\Models\\UserInfo', 'created', 72, NULL, NULL, '{\"attributes\":{\"id\":72,\"user_id\":72,\"avatar\":null,\"company\":\"Greenholt-Prohaska\",\"phone\":\"541.910.9333\",\"website\":\"https:\\/\\/kiehn.info\\/consectetur-aliquid-aut-qui-labore.html\",\"country\":\"LT\",\"language\":\"xh\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:26.000000Z\",\"updated_at\":\"2023-01-30T11:05:26.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(175, 'default', 'created', 'App\\Models\\UserInfo', 'created', 73, NULL, NULL, '{\"attributes\":{\"id\":73,\"user_id\":73,\"avatar\":null,\"company\":\"Ratke, Beatty and Cole\",\"phone\":\"(364) 738-5642\",\"website\":\"http:\\/\\/www.weimann.com\\/\",\"country\":\"SG\",\"language\":\"ee\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(176, 'default', 'created', 'App\\Models\\UserInfo', 'created', 74, NULL, NULL, '{\"attributes\":{\"id\":74,\"user_id\":74,\"avatar\":null,\"company\":\"Hackett Ltd\",\"phone\":\"(201) 658-4511\",\"website\":\"http:\\/\\/www.roberts.com\\/amet-occaecati-temporibus-tempore-officiis-molestias-reprehenderit.html\",\"country\":\"NI\",\"language\":\"km\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(177, 'default', 'created', 'App\\Models\\UserInfo', 'created', 75, NULL, NULL, '{\"attributes\":{\"id\":75,\"user_id\":75,\"avatar\":null,\"company\":\"Kuvalis LLC\",\"phone\":\"+1 (307) 840-2349\",\"website\":\"http:\\/\\/www.kuhlman.info\\/\",\"country\":\"BD\",\"language\":\"ks\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(178, 'default', 'created', 'App\\Models\\UserInfo', 'created', 76, NULL, NULL, '{\"attributes\":{\"id\":76,\"user_id\":76,\"avatar\":null,\"company\":\"Morar PLC\",\"phone\":\"+1-281-268-0472\",\"website\":\"http:\\/\\/www.mcdermott.org\\/\",\"country\":\"JP\",\"language\":\"kv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(179, 'default', 'created', 'App\\Models\\UserInfo', 'created', 77, NULL, NULL, '{\"attributes\":{\"id\":77,\"user_id\":77,\"avatar\":null,\"company\":\"Sawayn Inc\",\"phone\":\"540.823.5176\",\"website\":\"http:\\/\\/lubowitz.biz\\/magnam-non-minima-sint-aut-vel-mollitia\",\"country\":\"BT\",\"language\":\"fo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(180, 'default', 'created', 'App\\Models\\UserInfo', 'created', 78, NULL, NULL, '{\"attributes\":{\"id\":78,\"user_id\":78,\"avatar\":null,\"company\":\"Schultz-Wisozk\",\"phone\":\"(313) 432-7541\",\"website\":\"https:\\/\\/www.hamill.com\\/earum-architecto-eius-magni-repellendus-facilis\",\"country\":\"TF\",\"language\":\"vo\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(181, 'default', 'created', 'App\\Models\\UserInfo', 'created', 79, NULL, NULL, '{\"attributes\":{\"id\":79,\"user_id\":79,\"avatar\":null,\"company\":\"Donnelly, Will and Kshlerin\",\"phone\":\"+1 (612) 844-6864\",\"website\":\"http:\\/\\/oberbrunner.info\\/deserunt-nisi-debitis-omnis-minima-sint-est\",\"country\":\"ST\",\"language\":\"ss\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(182, 'default', 'created', 'App\\Models\\UserInfo', 'created', 80, NULL, NULL, '{\"attributes\":{\"id\":80,\"user_id\":80,\"avatar\":null,\"company\":\"Kohler-Feeney\",\"phone\":\"+1-575-441-8828\",\"website\":\"http:\\/\\/www.hackett.biz\\/dignissimos-perferendis-dignissimos-repudiandae-dolorum-voluptatibus-tenetur-repellendus\",\"country\":\"CK\",\"language\":\"it\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:27', '2023-01-30 05:05:27'),
(183, 'default', 'created', 'App\\Models\\UserInfo', 'created', 81, NULL, NULL, '{\"attributes\":{\"id\":81,\"user_id\":81,\"avatar\":null,\"company\":\"Walker-Kiehn\",\"phone\":\"+1-908-471-6801\",\"website\":\"http:\\/\\/www.daugherty.biz\\/\",\"country\":\"QA\",\"language\":\"ty\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:27.000000Z\",\"updated_at\":\"2023-01-30T11:05:27.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(184, 'default', 'created', 'App\\Models\\UserInfo', 'created', 82, NULL, NULL, '{\"attributes\":{\"id\":82,\"user_id\":82,\"avatar\":null,\"company\":\"Koch, Klocko and Crooks\",\"phone\":\"1-248-533-6135\",\"website\":\"https:\\/\\/bednar.com\\/eveniet-voluptatibus-eos-cumque-et-aliquid-dolorem-autem-cupiditate.html\",\"country\":\"SN\",\"language\":\"eu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(185, 'default', 'created', 'App\\Models\\UserInfo', 'created', 83, NULL, NULL, '{\"attributes\":{\"id\":83,\"user_id\":83,\"avatar\":null,\"company\":\"Parisian-Wilkinson\",\"phone\":\"(820) 313-9835\",\"website\":\"https:\\/\\/bartoletti.com\\/beatae-eveniet-et-fugit-nemo-cupiditate-quisquam.html\",\"country\":\"HN\",\"language\":\"ro\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(186, 'default', 'created', 'App\\Models\\UserInfo', 'created', 84, NULL, NULL, '{\"attributes\":{\"id\":84,\"user_id\":84,\"avatar\":null,\"company\":\"Shields, Gleason and Hegmann\",\"phone\":\"1-425-566-5458\",\"website\":\"http:\\/\\/www.rohan.org\\/doloremque-modi-dolores-eum-pariatur-placeat-iste-iste-sint\",\"country\":\"SM\",\"language\":\"cs\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(187, 'default', 'created', 'App\\Models\\UserInfo', 'created', 85, NULL, NULL, '{\"attributes\":{\"id\":85,\"user_id\":85,\"avatar\":null,\"company\":\"Legros, Haag and Mills\",\"phone\":\"(323) 616-3184\",\"website\":\"http:\\/\\/zieme.com\\/\",\"country\":\"IL\",\"language\":\"ff\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(188, 'default', 'created', 'App\\Models\\UserInfo', 'created', 86, NULL, NULL, '{\"attributes\":{\"id\":86,\"user_id\":86,\"avatar\":null,\"company\":\"Prosacco, Little and Murray\",\"phone\":\"347.800.2252\",\"website\":\"http:\\/\\/www.hansen.com\\/\",\"country\":\"RS\",\"language\":\"uk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(189, 'default', 'created', 'App\\Models\\UserInfo', 'created', 87, NULL, NULL, '{\"attributes\":{\"id\":87,\"user_id\":87,\"avatar\":null,\"company\":\"Osinski Inc\",\"phone\":\"+14783423721\",\"website\":\"http:\\/\\/ziemann.biz\\/\",\"country\":\"BJ\",\"language\":\"ee\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:28', '2023-01-30 05:05:28'),
(190, 'default', 'created', 'App\\Models\\UserInfo', 'created', 88, NULL, NULL, '{\"attributes\":{\"id\":88,\"user_id\":88,\"avatar\":null,\"company\":\"Considine and Sons\",\"phone\":\"+1-216-701-3632\",\"website\":\"http:\\/\\/www.green.biz\\/animi-qui-doloribus-provident-recusandae-quia-quidem-inventore.html\",\"country\":\"AU\",\"language\":\"su\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:28.000000Z\",\"updated_at\":\"2023-01-30T11:05:28.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(191, 'default', 'created', 'App\\Models\\UserInfo', 'created', 89, NULL, NULL, '{\"attributes\":{\"id\":89,\"user_id\":89,\"avatar\":null,\"company\":\"Harvey, Flatley and Altenwerth\",\"phone\":\"1-567-483-5250\",\"website\":\"http:\\/\\/www.auer.com\\/ipsa-et-adipisci-fugiat-sint-doloremque-quos-reprehenderit.html\",\"country\":\"MC\",\"language\":\"hu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(192, 'default', 'created', 'App\\Models\\UserInfo', 'created', 90, NULL, NULL, '{\"attributes\":{\"id\":90,\"user_id\":90,\"avatar\":null,\"company\":\"Hirthe PLC\",\"phone\":\"608-294-0876\",\"website\":\"http:\\/\\/www.kemmer.com\\/quis-magni-voluptas-vel-molestias-tenetur.html\",\"country\":\"KE\",\"language\":\"uk\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(193, 'default', 'created', 'App\\Models\\UserInfo', 'created', 91, NULL, NULL, '{\"attributes\":{\"id\":91,\"user_id\":91,\"avatar\":null,\"company\":\"Bartoletti-Hauck\",\"phone\":\"+1-612-539-4775\",\"website\":\"http:\\/\\/www.gislason.org\\/deserunt-consequuntur-commodi-et-in-voluptate.html\",\"country\":\"MN\",\"language\":\"tl\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(194, 'default', 'created', 'App\\Models\\UserInfo', 'created', 92, NULL, NULL, '{\"attributes\":{\"id\":92,\"user_id\":92,\"avatar\":null,\"company\":\"Hermiston, Corkery and Cronin\",\"phone\":\"+19525242128\",\"website\":\"http:\\/\\/gottlieb.net\\/aliquid-ullam-enim-debitis-perspiciatis-nesciunt-necessitatibus-enim\",\"country\":\"AO\",\"language\":\"za\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(195, 'default', 'created', 'App\\Models\\UserInfo', 'created', 93, NULL, NULL, '{\"attributes\":{\"id\":93,\"user_id\":93,\"avatar\":null,\"company\":\"Kuvalis, Keeling and Ullrich\",\"phone\":\"402.552.0885\",\"website\":\"http:\\/\\/www.pouros.biz\\/quia-repellendus-dolorem-vitae-deleniti\",\"country\":\"SM\",\"language\":\"sw\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(196, 'default', 'created', 'App\\Models\\UserInfo', 'created', 94, NULL, NULL, '{\"attributes\":{\"id\":94,\"user_id\":94,\"avatar\":null,\"company\":\"Schmitt, Towne and Carter\",\"phone\":\"(410) 281-2156\",\"website\":\"http:\\/\\/reynolds.biz\\/commodi-rerum-deleniti-quaerat-aut-laboriosam-quia.html\",\"country\":\"HT\",\"language\":\"kl\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(197, 'default', 'created', 'App\\Models\\UserInfo', 'created', 95, NULL, NULL, '{\"attributes\":{\"id\":95,\"user_id\":95,\"avatar\":null,\"company\":\"Williamson-Hane\",\"phone\":\"1-480-968-3427\",\"website\":\"https:\\/\\/jenkins.net\\/consectetur-quis-quasi-omnis-tenetur-cupiditate-in.html\",\"country\":\"PG\",\"language\":\"mn\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:29', '2023-01-30 05:05:29'),
(198, 'default', 'created', 'App\\Models\\UserInfo', 'created', 96, NULL, NULL, '{\"attributes\":{\"id\":96,\"user_id\":96,\"avatar\":null,\"company\":\"McGlynn-Donnelly\",\"phone\":\"440.371.9553\",\"website\":\"http:\\/\\/www.crist.biz\\/\",\"country\":\"LC\",\"language\":\"jv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:29.000000Z\",\"updated_at\":\"2023-01-30T11:05:29.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(199, 'default', 'created', 'App\\Models\\UserInfo', 'created', 97, NULL, NULL, '{\"attributes\":{\"id\":97,\"user_id\":97,\"avatar\":null,\"company\":\"Schinner LLC\",\"phone\":\"380.336.9448\",\"website\":\"http:\\/\\/hauck.com\\/non-ratione-sapiente-maiores-aut\",\"country\":\"LI\",\"language\":\"ku\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(200, 'default', 'created', 'App\\Models\\UserInfo', 'created', 98, NULL, NULL, '{\"attributes\":{\"id\":98,\"user_id\":98,\"avatar\":null,\"company\":\"Wilkinson, Nolan and Predovic\",\"phone\":\"+1-743-381-5031\",\"website\":\"http:\\/\\/www.spencer.org\\/expedita-molestiae-nulla-neque-accusantium-illo\",\"country\":\"GE\",\"language\":\"is\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(201, 'default', 'created', 'App\\Models\\UserInfo', 'created', 99, NULL, NULL, '{\"attributes\":{\"id\":99,\"user_id\":99,\"avatar\":null,\"company\":\"Hegmann-Heathcote\",\"phone\":\"865-879-1534\",\"website\":\"http:\\/\\/runolfsson.com\\/maxime-laborum-voluptatum-totam-itaque-aspernatur-sed-illum.html\",\"country\":\"MN\",\"language\":\"gu\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(202, 'default', 'created', 'App\\Models\\UserInfo', 'created', 100, NULL, NULL, '{\"attributes\":{\"id\":100,\"user_id\":100,\"avatar\":null,\"company\":\"Block, Daugherty and Lynch\",\"phone\":\"+1.364.915.1307\",\"website\":\"http:\\/\\/www.maggio.com\\/\",\"country\":\"SS\",\"language\":\"so\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(203, 'default', 'created', 'App\\Models\\UserInfo', 'created', 101, NULL, NULL, '{\"attributes\":{\"id\":101,\"user_id\":101,\"avatar\":null,\"company\":\"Blick-Wiza\",\"phone\":\"+15019497591\",\"website\":\"http:\\/\\/www.klocko.net\\/\",\"country\":\"AI\",\"language\":\"oj\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(204, 'default', 'created', 'App\\Models\\UserInfo', 'created', 102, NULL, NULL, '{\"attributes\":{\"id\":102,\"user_id\":102,\"avatar\":null,\"company\":\"Reichert LLC\",\"phone\":\"520-424-4966\",\"website\":\"https:\\/\\/oconnell.com\\/qui-quis-minus-et.html\",\"country\":\"BD\",\"language\":\"lv\",\"timezone\":null,\"currency\":null,\"communication\":null,\"marketing\":null,\"created_at\":\"2023-01-30T11:05:30.000000Z\",\"updated_at\":\"2023-01-30T11:05:30.000000Z\"}}', NULL, '2023-01-30 05:05:30', '2023-01-30 05:05:30'),
(205, 'default', 'created', 'App\\Models\\User', 'created', 103, NULL, NULL, '{\"attributes\":{\"id\":103,\"first_name\":\"MD\",\"last_name\":\"Masum\",\"email\":\"admin@masum.com\",\"email_verified_at\":null,\"password\":\"$2y$10$AfzXoCcyhMGbQnmKfBMOb.XjIEcEmIIFm7oA8T0CXSAKdl7btQwcG\",\"api_token\":null,\"remember_token\":null,\"created_at\":\"2023-01-31T09:59:20.000000Z\",\"updated_at\":\"2023-01-31T09:59:20.000000Z\"}}', NULL, '2023-01-31 03:59:20', '2023-01-31 03:59:20'),
(206, 'default', 'created', 'App\\Models\\UserInfo', 'created', 103, 'App\\Models\\User', 103, '{\"attributes\":{\"id\":103,\"user_id\":103,\"avatar\":\"images\\/m4QvXPPU5YcsWG4EOSVsQRBjI5eMIxL4CblmuFzN.png\",\"company\":null,\"phone\":\"016\",\"website\":null,\"country\":\"BD\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"currency\":null,\"communication\":{\"email\":\"1\",\"phone\":\"0\"},\"marketing\":0,\"created_at\":\"2023-01-31T10:05:00.000000Z\",\"updated_at\":\"2023-01-31T10:05:00.000000Z\"}}', NULL, '2023-01-31 04:05:00', '2023-01-31 04:05:00'),
(207, 'default', 'updated', 'App\\Models\\User', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"first_name\":\"GCL\",\"last_name\":\"Admin\",\"updated_at\":\"2023-01-31T10:56:45.000000Z\"},\"old\":{\"first_name\":\"MD\",\"last_name\":\"Masum\",\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-31 04:56:45', '2023-01-31 04:56:45'),
(208, 'default', 'updated', 'App\\Models\\UserInfo', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"avatar\":\"images\\/DA6oVfAqPOcSR4AjGbo1TryIlKbWmthbgXhgCtpx.png\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"communication\":{\"email\":\"0\",\"phone\":\"0\"},\"marketing\":0,\"updated_at\":\"2023-01-31T10:56:45.000000Z\"},\"old\":{\"avatar\":null,\"language\":\"ik\",\"timezone\":null,\"communication\":null,\"marketing\":null,\"updated_at\":\"2023-01-30T11:05:03.000000Z\"}}', NULL, '2023-01-31 04:56:45', '2023-01-31 04:56:45'),
(209, 'default', 'created', 'App\\Models\\User', 'created', 104, NULL, NULL, '{\"attributes\":{\"id\":104,\"first_name\":\"Global\",\"last_name\":\"Admin\",\"email\":\"info@gslcorporate.com\",\"email_verified_at\":null,\"password\":\"$2y$10$OaFIVnac4qSoGiPY1ztUf.h7oNyGse1Ub9hGPrFEmVOnJIX5qTVye\",\"api_token\":null,\"remember_token\":null,\"created_at\":\"2023-01-31T12:24:50.000000Z\",\"updated_at\":\"2023-01-31T12:24:50.000000Z\"}}', NULL, '2023-01-31 06:24:50', '2023-01-31 06:24:50'),
(210, 'default', 'created', 'App\\Models\\UserInfo', 'created', 104, 'App\\Models\\User', 104, '{\"attributes\":{\"id\":104,\"user_id\":104,\"avatar\":\"images\\/8qtHFI0f9xOIss1lEz0Z62AGc3q3I5OyPrLF2ONx.png\",\"company\":null,\"phone\":\"01711 531786\",\"website\":null,\"country\":\"BD\",\"language\":\"en-gb\",\"timezone\":\"Dhaka\",\"currency\":null,\"communication\":{\"email\":\"0\",\"phone\":\"0\"},\"marketing\":0,\"created_at\":\"2023-01-31T12:27:48.000000Z\",\"updated_at\":\"2023-01-31T12:27:48.000000Z\"}}', NULL, '2023-01-31 06:27:48', '2023-01-31 06:27:48');

-- --------------------------------------------------------

--
-- Table structure for table `additionalservices`
--

CREATE TABLE `additionalservices` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allowances`
--

CREATE TABLE `allowances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `allowance_option` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `type` tinyint(4) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `allowances`
--

INSERT INTO `allowances` (`id`, `employee_id`, `allowance_option`, `title`, `amount`, `percentage`, `type`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Allowance Title', 600.00, '1.00', 2, 1, '2023-07-19 07:49:26', '2023-07-19 10:31:04'),
(3, 1, 2, 'Allowance', 24000.00, '0.00', 1, 1, '2023-07-19 07:55:56', '2023-07-19 10:26:20'),
(4, 1, 2, 'Allowance Title', 18000.00, '30.00', 2, 1, '2023-07-19 10:19:18', '2023-07-19 10:28:23'),
(5, 2, 1, 'Allowance Title', 1500.00, '5.00', 2, 1, '2023-07-24 12:26:28', '2023-07-24 12:26:28'),
(1, 1, 2, 'Allowance Title', 600.00, '1.00', 2, 1, '2023-07-19 07:49:26', '2023-07-19 10:31:04'),
(3, 1, 2, 'Allowance', 24000.00, '0.00', 1, 1, '2023-07-19 07:55:56', '2023-07-19 10:26:20'),
(4, 1, 2, 'Allowance Title', 18000.00, '30.00', 2, 1, '2023-07-19 10:19:18', '2023-07-19 10:28:23'),
(5, 2, 1, 'Allowance Title', 1500.00, '5.00', 2, 1, '2023-07-24 12:26:28', '2023-07-24 12:26:28'),
(1, 1, 2, 'Allowance Title', 600.00, '1.00', 2, 1, '2023-07-19 07:49:26', '2023-07-19 10:31:04'),
(3, 1, 2, 'Allowance', 24000.00, '0.00', 1, 1, '2023-07-19 07:55:56', '2023-07-19 10:26:20'),
(4, 1, 2, 'Allowance Title', 18000.00, '30.00', 2, 1, '2023-07-19 10:19:18', '2023-07-19 10:28:23'),
(5, 2, 1, 'Allowance Title', 1500.00, '5.00', 2, 1, '2023-07-24 12:26:28', '2023-07-24 12:26:28');

-- --------------------------------------------------------

--
-- Table structure for table `allowance_options`
--

CREATE TABLE `allowance_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `allowance_options`
--

INSERT INTO `allowance_options` (`id`, `name`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Allowance Option 1', 1, 1, '2023-07-13 07:20:31', '2023-07-13 08:27:06'),
(2, 'Allowance Option 2', 1, 1, '2023-07-13 07:20:39', '2023-07-13 07:20:44'),
(1, 'Allowance Option 1', 1, 1, '2023-07-13 07:20:31', '2023-07-13 08:27:06'),
(2, 'Allowance Option 2', 1, 1, '2023-07-13 07:20:39', '2023-07-13 07:20:44'),
(1, 'Allowance Option 1', 1, 1, '2023-07-13 07:20:31', '2023-07-13 08:27:06'),
(2, 'Allowance Option 2', 1, 1, '2023-07-13 07:20:39', '2023-07-13 07:20:44');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(20) UNSIGNED NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `start_date`, `end_date`, `description`, `file_path`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'New Announcement', '2023-12-16', '2023-12-17', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', 'public/announcements/18-12-2023-11-19-18_Conveyance Bill.pdf', 1, '2023-12-18 05:19:18', '2023-12-18 05:19:18'),
(2, 'New Announcement 19-12-23', '2023-12-19', '2023-12-19', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', 'public/announcements/18-12-2023-11-45-59_Conveyance Bill.pdf', 1, '2023-12-18 05:45:59', '2023-12-18 05:45:59'),
(3, 'New Announcement 18-12-23', '2023-12-18', '2023-12-18', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', 'public/announcements/18-12-2023-11-47-03_Conveyance Bill.jpg', 1, '2023-12-18 05:47:03', '2023-12-18 05:47:03'),
(4, 'New Announcement 1010', '2023-12-18', '2023-12-31', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'public/announcements/19-12-2023-07-04-36_Conveyance Bill.pdf', 1, '2023-12-19 01:04:36', '2023-12-19 01:04:36'),
(6, '25 Dec', '2023-12-24', '2023-12-25', 'Holiday 25 dec 2023', NULL, 1, '2023-12-24 01:38:42', '2023-12-24 01:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_details`
--

CREATE TABLE `announcement_details` (
  `id` bigint(20) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `announcement_details`
--

INSERT INTO `announcement_details` (`id`, `announcement_id`, `employee_id`, `status`) VALUES
(1, 1, 3, 1),
(2, 1, 5, 1),
(3, 1, 6, 1),
(4, 1, 9, 1),
(5, 1, 10, 1),
(6, 1, 12, 1),
(7, 1, 14, 1),
(8, 1, 16, 1),
(9, 2, 3, 1),
(10, 2, 5, 1),
(11, 2, 6, 1),
(12, 2, 9, 1),
(13, 2, 10, 1),
(14, 2, 12, 1),
(15, 2, 14, 1),
(16, 2, 16, 1),
(17, 3, 3, 1),
(18, 3, 5, 1),
(19, 3, 6, 1),
(20, 3, 9, 1),
(21, 3, 10, 1),
(22, 3, 12, 1),
(23, 3, 14, 1),
(24, 3, 16, 1),
(25, 4, 3, 1),
(26, 4, 5, 1),
(27, 4, 6, 1),
(28, 4, 9, 1),
(29, 4, 10, 1),
(30, 4, 12, 1),
(31, 4, 14, 1),
(32, 4, 16, 1),
(41, 6, 3, 1),
(42, 6, 5, 1),
(43, 6, 6, 1),
(44, 6, 1, 1),
(45, 6, 2, 1),
(46, 6, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) NOT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `employee_code` int(11) DEFAULT NULL,
  `date_time_record` datetime DEFAULT NULL,
  `date_only_record` date DEFAULT NULL,
  `done_by` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `machine_id`, `employee_code`, `date_time_record`, `date_only_record`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-10-04 11:56:51', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(2, 1, 1, '2023-10-04 11:57:08', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(3, 1, 1, '2023-10-04 12:17:34', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(4, 1, 1, '2023-10-04 12:44:11', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(5, 1, 1, '2023-10-04 12:44:38', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(6, 1, 1, '2023-10-04 12:44:50', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(7, 1, 1, '2023-10-04 12:56:45', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(8, 1, 1, '2023-10-04 12:56:51', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(9, 1, 1, '2023-10-04 13:14:40', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(10, 1, 1, '2023-10-04 13:17:06', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(11, 1, 1, '2023-10-04 13:17:14', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(12, 1, 1, '2023-10-04 13:20:41', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(13, 1, 1, '2023-10-04 13:20:42', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(14, 1, 1, '2023-10-04 13:35:25', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(15, 1, 1, '2023-10-04 13:35:40', '2023-10-04', NULL, '2023-10-04 12:08:01', NULL),
(16, 1, 1, '2023-10-04 13:35:51', '2023-10-04', NULL, '2023-10-04 12:08:02', NULL),
(17, 1, 1, '2023-10-04 13:36:45', '2023-10-04', NULL, '2023-10-04 12:08:02', NULL),
(18, 1, 1, '2023-10-04 13:37:57', '2023-10-04', NULL, '2023-10-04 12:08:02', NULL),
(19, 1, 1, '2023-10-04 16:22:38', '2023-10-04', NULL, '2023-10-04 12:08:02', NULL),
(20, 1, 1, '2023-10-04 16:23:06', '2023-10-04', NULL, '2023-10-04 12:08:02', NULL),
(21, 1, 1, '2023-10-04 16:23:19', '2023-10-04', NULL, '2023-10-04 12:08:02', NULL),
(22, 1, 1, '2023-10-04 16:34:24', '2023-10-04', NULL, '2023-10-04 12:08:02', NULL),
(23, 1, 1, '2023-10-04 16:43:25', '2023-10-04', NULL, '2023-10-04 12:08:02', NULL),
(24, 1, 1, '2023-09-07 13:51:10', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(25, 1, 1, '2023-09-07 13:51:43', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(26, 1, 1, '2023-09-07 13:51:47', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(27, 1, 1, '2023-09-07 13:51:49', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(28, 1, 1, '2023-09-07 13:53:45', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(29, 1, 1, '2023-09-07 15:02:34', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(30, 1, 1, '2023-09-07 15:02:37', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(31, 1, 1, '2023-09-07 15:02:43', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(32, 1, 1, '2023-09-07 15:02:48', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(33, 1, 1, '2023-09-07 15:02:53', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(34, 1, 1, '2023-09-07 15:07:02', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(35, 1, 1, '2023-09-07 15:27:50', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(36, 1, 1, '2023-09-07 15:27:53', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(37, 1, 1, '2023-09-07 15:27:55', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(38, 1, 1, '2023-09-07 15:27:56', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(39, 1, 1, '2023-09-07 15:27:57', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(40, 1, 1, '2023-09-07 15:27:58', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(41, 1, 1, '2023-09-07 15:27:59', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(42, 1, 1, '2023-09-07 15:28:01', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(43, 1, 2, '2023-09-07 09:35:35', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(44, 1, 1, '2023-09-07 15:35:50', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(45, 1, 2, '2023-09-07 15:35:53', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(46, 1, 1, '2023-09-07 16:01:34', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(47, 1, 1, '2023-09-07 16:14:29', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(48, 1, 1, '2023-09-07 16:17:00', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(49, 1, 1, '2023-09-07 17:15:23', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(50, 1, 1, '2023-09-07 17:42:34', '2023-09-07', NULL, '2023-10-04 12:08:03', NULL),
(51, 1, 1, '2023-09-07 18:13:02', '2023-09-07', NULL, '2023-10-04 12:08:04', NULL),
(52, 1, 1, '2023-09-07 18:13:04', '2023-09-07', NULL, '2023-10-04 12:08:04', NULL),
(53, 1, 1, '2023-09-07 18:30:20', '2023-09-07', NULL, '2023-10-04 12:08:04', NULL),
(54, 1, 1, '2023-09-07 18:30:25', '2023-09-07', NULL, '2023-10-04 12:08:04', NULL),
(55, 1, 1, '2023-09-07 18:32:08', '2023-09-07', NULL, '2023-10-04 12:08:04', NULL),
(56, 1, 1, '2023-09-07 18:32:11', '2023-09-07', NULL, '2023-10-04 12:08:04', NULL),
(57, 1, 1, '2023-09-07 18:32:14', '2023-09-07', NULL, '2023-10-04 12:08:04', NULL),
(58, 1, 1, '2023-09-10 10:55:03', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(59, 1, 1, '2023-09-10 10:55:19', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(60, 1, 1, '2023-09-10 10:56:32', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(61, 1, 1, '2023-09-10 10:56:36', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(62, 1, 1, '2023-09-10 10:56:38', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(63, 1, 1, '2023-09-10 10:56:40', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(64, 1, 1, '2023-09-10 11:17:03', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(65, 1, 1, '2023-09-10 11:17:04', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(66, 1, 1, '2023-09-10 11:17:15', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(67, 1, 1, '2023-09-10 11:38:47', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(68, 1, 2, '2023-09-10 12:55:00', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(69, 1, 1, '2023-09-10 13:34:07', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(70, 1, 1, '2023-09-10 13:35:04', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(71, 1, 1, '2023-09-10 13:35:23', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(73, 1, 1, '2023-09-10 15:28:15', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(74, 1, 1, '2023-09-10 18:05:50', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(75, 1, 1, '2023-09-10 18:06:46', '2023-09-10', NULL, '2023-10-04 12:08:04', NULL),
(76, 1, 1, '2023-09-10 18:15:46', '2023-09-10', NULL, '2023-10-04 12:08:05', NULL),
(77, 1, 1, '2023-09-10 18:18:57', '2023-09-10', NULL, '2023-10-04 12:08:05', NULL),
(78, 1, 1, '2023-09-10 18:24:08', '2023-09-10', NULL, '2023-10-04 12:08:05', NULL),
(79, 1, 1, '2023-09-10 18:35:32', '2023-09-10', NULL, '2023-10-04 12:08:05', NULL),
(80, 1, 1, '2023-09-10 18:42:16', '2023-09-10', NULL, '2023-10-04 12:08:05', NULL),
(81, 1, 1, '2023-09-11 12:19:18', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(82, 1, 1, '2023-09-11 12:19:27', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(83, 1, 1, '2023-09-11 12:24:56', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(84, 1, 1, '2023-09-11 12:24:58', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(85, 1, 1, '2023-09-11 12:26:35', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(86, 1, 1, '2023-09-11 12:27:38', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(87, 1, 1, '2023-09-11 12:32:47', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(88, 1, 1, '2023-09-11 12:32:54', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(89, 1, 2, '2023-09-11 12:32:59', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(90, 1, 1, '2023-09-11 12:45:46', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(91, 1, 1, '2023-09-11 12:46:23', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(92, 1, 1, '2023-09-11 12:46:27', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(93, 1, 1, '2023-09-11 12:46:30', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(94, 1, 1, '2023-09-11 13:03:38', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(95, 1, 2, '2023-09-11 13:06:16', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(96, 1, 2, '2023-09-11 13:07:07', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(97, 1, 2, '2023-09-11 13:07:42', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(99, 1, 1, '2023-09-11 17:07:41', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(100, 1, 1, '2023-09-11 17:07:43', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(101, 1, 1, '2023-09-11 17:07:46', '2023-09-11', NULL, '2023-10-04 12:08:05', NULL),
(102, 1, 1, '2023-09-11 17:23:49', '2023-09-11', NULL, '2023-10-04 12:08:06', NULL),
(103, 1, 1, '2023-09-11 17:24:02', '2023-09-11', NULL, '2023-10-04 12:08:06', NULL),
(104, 1, 1, '2023-09-11 17:37:56', '2023-09-11', NULL, '2023-10-04 12:08:06', NULL),
(105, 1, 1, '2023-09-11 18:15:34', '2023-09-11', NULL, '2023-10-04 12:08:06', NULL),
(106, 1, 1, '2023-09-11 18:16:31', '2023-09-11', NULL, '2023-10-04 12:08:06', NULL),
(107, 1, 1, '2023-09-13 13:30:22', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(108, 1, 1, '2023-09-13 13:30:24', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(109, 1, 1, '2023-09-13 15:39:34', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(110, 1, 1, '2023-09-13 15:39:45', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(111, 1, 1, '2023-09-13 16:22:45', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(112, 1, 2, '2023-09-13 16:50:03', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(113, 1, 1, '2023-09-13 16:55:40', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(114, 1, 1, '2023-09-13 17:41:48', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(115, 1, 1, '2023-09-13 17:41:50', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(116, 1, 1, '2023-09-13 17:41:51', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(117, 1, 1, '2023-09-13 17:49:50', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(118, 1, 1, '2023-09-13 17:49:53', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(119, 1, 1, '2023-09-13 17:56:33', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(120, 1, 1, '2023-09-13 17:56:44', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(121, 1, 1, '2023-09-13 18:27:15', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(122, 1, 1, '2023-09-13 18:27:33', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(123, 1, 1, '2023-09-13 18:27:34', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(124, 1, 1, '2023-09-13 18:27:43', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(125, 1, 1, '2023-09-13 18:27:47', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(126, 1, 1, '2023-09-13 18:31:10', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(127, 1, 1, '2023-09-13 18:49:40', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(128, 1, 1, '2023-09-13 18:49:41', '2023-09-13', NULL, '2023-10-04 12:08:06', NULL),
(129, 1, 1, '2023-09-14 14:52:14', '2023-09-14', NULL, '2023-10-04 12:08:07', NULL),
(130, 1, 2, '2023-09-14 15:06:34', '2023-09-14', NULL, '2023-10-04 12:08:07', NULL),
(131, 1, 1, '2023-09-14 16:01:24', '2023-09-14', NULL, '2023-10-04 12:08:07', NULL),
(132, 1, 1, '2023-09-17 11:12:43', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(133, 1, 1, '2023-09-17 11:16:46', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(134, 1, 2, '2023-09-17 11:17:01', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(135, 1, 1, '2023-09-17 11:38:08', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(136, 1, 1, '2023-09-17 12:04:56', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(137, 1, 1, '2023-09-17 12:31:06', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(138, 1, 1, '2023-09-17 12:31:10', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(139, 1, 1, '2023-09-17 12:31:12', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(140, 1, 1, '2023-09-17 12:31:16', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(141, 1, 1, '2023-09-17 12:31:18', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(142, 1, 1, '2023-09-17 12:31:19', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(143, 1, 1, '2023-09-17 12:31:20', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(144, 1, 1, '2023-09-17 12:31:22', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(145, 1, 1, '2023-09-17 12:31:23', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(146, 1, 1, '2023-09-17 12:31:26', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(147, 1, 1, '2023-09-17 12:31:27', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(148, 1, 1, '2023-09-17 12:31:29', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(149, 1, 1, '2023-09-17 13:41:32', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(150, 1, 1, '2023-09-17 13:41:33', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(151, 1, 1, '2023-09-17 13:41:34', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(152, 1, 1, '2023-09-17 13:41:36', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(153, 1, 1, '2023-09-17 13:41:37', '2023-09-17', NULL, '2023-10-04 12:08:07', NULL),
(154, 1, 1, '2023-09-17 13:41:40', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(155, 1, 1, '2023-09-17 13:41:41', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(156, 1, 1, '2023-09-17 13:41:43', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(157, 1, 1, '2023-09-17 13:41:44', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(158, 1, 1, '2023-09-17 13:41:45', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(159, 1, 1, '2023-09-17 13:41:46', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(160, 1, 1, '2023-09-17 13:41:48', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(161, 1, 1, '2023-09-17 13:41:49', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(162, 1, 1, '2023-09-17 13:44:12', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(163, 1, 1, '2023-09-17 13:44:14', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(164, 1, 1, '2023-09-17 16:04:32', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(165, 1, 1, '2023-09-17 16:12:03', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(167, 1, 1, '2023-09-17 16:22:25', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(168, 1, 1, '2023-09-17 16:28:47', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(169, 1, 1, '2023-09-17 16:29:15', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(170, 1, 1, '2023-09-17 16:33:22', '2023-09-17', NULL, '2023-10-04 12:08:08', NULL),
(171, 1, 1, '2023-09-17 16:35:39', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(172, 1, 1, '2023-09-17 16:37:26', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(173, 1, 1, '2023-09-17 16:40:58', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(174, 1, 1, '2023-09-17 17:05:39', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(175, 1, 1, '2023-09-17 17:05:56', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(176, 1, 1, '2023-09-17 17:08:35', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(177, 1, 1, '2023-09-17 17:10:04', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(178, 1, 1, '2023-09-17 17:10:28', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(179, 1, 1, '2023-09-17 17:13:19', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(180, 1, 1, '2023-09-17 17:14:27', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(181, 1, 1, '2023-09-17 17:14:28', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(182, 1, 1, '2023-09-17 17:14:29', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(183, 1, 1, '2023-09-17 17:14:30', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(184, 1, 1, '2023-09-17 17:14:31', '2023-09-17', NULL, '2023-10-04 12:08:09', NULL),
(185, 1, 1, '2023-09-17 17:14:33', '2023-09-17', NULL, '2023-10-04 12:08:10', NULL),
(186, 1, 1, '2023-09-17 17:14:34', '2023-09-17', NULL, '2023-10-04 12:08:10', NULL),
(187, 1, 2, '2023-09-17 17:22:22', '2023-09-17', NULL, '2023-10-04 12:08:10', NULL),
(188, 1, 1, '2023-09-18 10:56:46', '2023-09-18', NULL, '2023-10-04 12:08:10', NULL),
(189, 1, 1, '2023-09-18 11:09:28', '2023-09-18', NULL, '2023-10-04 12:08:10', NULL),
(190, 1, 2, '2023-09-18 11:18:56', '2023-09-18', NULL, '2023-10-04 12:08:10', NULL),
(191, 1, 1, '2023-09-18 12:24:39', '2023-09-18', NULL, '2023-10-04 12:08:10', NULL),
(192, 1, 1, '2023-09-18 13:18:20', '2023-09-18', NULL, '2023-10-04 12:08:10', NULL),
(193, 1, 1, '2023-09-18 13:19:07', '2023-09-18', NULL, '2023-10-04 12:08:10', NULL),
(194, 1, 1, '2023-09-18 13:20:21', '2023-09-18', NULL, '2023-10-04 12:08:10', NULL),
(195, 1, 1, '2023-09-20 16:12:15', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(196, 1, 1, '2023-09-20 16:13:35', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(197, 1, 1, '2023-09-20 16:14:51', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(198, 1, 1, '2023-09-20 16:15:02', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(199, 1, 1, '2023-09-20 16:15:03', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(200, 1, 1, '2023-09-20 16:15:06', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(201, 1, 1, '2023-09-20 16:15:07', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(202, 1, 1, '2023-09-20 16:15:10', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(203, 1, 1, '2023-09-20 16:31:24', '2023-09-20', NULL, '2023-10-04 12:08:10', NULL),
(205, 1, 1, '2023-09-27 17:33:14', '2023-09-27', NULL, '2023-10-04 12:08:10', NULL),
(206, 1, 1, '2023-09-27 17:36:01', '2023-09-27', NULL, '2023-10-04 12:08:10', NULL),
(207, 1, 1, '2023-09-27 17:36:09', '2023-09-27', NULL, '2023-10-04 12:08:10', NULL),
(208, 1, 1, '2023-09-27 17:48:33', '2023-09-27', NULL, '2023-10-04 12:08:10', NULL),
(255, 1, 1, '2023-10-04 18:08:31', '2023-10-04', NULL, '2023-10-04 12:08:43', NULL),
(280, 1, 1, '2023-10-04 18:11:05', '2023-10-04', NULL, '2023-10-04 12:11:16', NULL),
(361, 1, 1, '2023-10-09 13:48:47', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(362, 1, 1, '2023-10-09 13:54:07', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(363, 1, 1, '2023-10-09 13:56:47', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(364, 1, 1, '2023-10-09 13:57:57', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(365, 1, 1, '2023-10-09 13:57:58', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(366, 1, 1, '2023-10-09 14:07:04', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(367, 1, 1, '2023-10-09 14:07:05', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(368, 1, 1, '2023-10-09 14:07:07', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(369, 1, 1, '2023-10-09 14:07:09', '2023-10-09', NULL, '2023-10-09 08:16:09', NULL),
(406, 1, 1, '2023-10-09 14:17:18', '2023-10-09', NULL, '2023-10-09 08:17:34', NULL),
(823, 1, 1, '2023-10-09 16:47:22', '2023-10-09', NULL, '2023-10-11 11:33:05', NULL),
(824, 1, 1, '2023-10-09 17:24:04', '2023-10-09', NULL, '2023-10-11 11:33:05', NULL),
(825, 1, 1, '2023-10-09 17:50:19', '2023-10-09', NULL, '2023-10-11 11:33:05', NULL),
(826, 1, 2, '2023-10-11 17:21:04', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(827, 1, 1, '2023-10-11 17:21:08', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(828, 1, 1, '2023-10-11 17:21:10', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(829, 1, 2, '2023-10-11 17:21:13', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(830, 1, 2, '2023-10-11 17:21:41', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(831, 1, 1, '2023-10-11 17:21:44', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(832, 1, 2, '2023-10-11 17:23:00', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(833, 1, 2, '2023-10-11 17:26:19', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(834, 1, 1, '2023-10-11 17:26:44', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(835, 1, 1, '2023-10-11 17:27:25', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(836, 1, 1, '2023-10-11 17:29:15', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(837, 1, 1, '2023-10-11 17:29:23', '2023-10-11', NULL, '2023-10-11 11:33:05', NULL),
(890, 1, 1, '2023-10-11 17:33:09', '2023-10-11', NULL, '2023-10-11 11:33:14', NULL),
(944, 1, 1, '2023-10-11 17:34:29', '2023-10-11', NULL, '2023-10-11 11:34:35', NULL),
(999, 1, 1, '2023-10-11 17:34:39', '2023-10-11', NULL, '2023-10-11 11:34:44', NULL),
(1055, 1, 1, '2023-10-11 17:34:47', '2023-10-11', NULL, '2023-10-11 11:34:53', NULL),
(1112, 1, 1, '2023-10-11 17:34:54', '2023-10-11', NULL, '2023-10-11 11:34:59', NULL),
(1170, 1, 1, '2023-10-11 17:35:12', '2023-10-11', NULL, '2023-10-11 11:35:17', NULL),
(1229, 1, 1, '2023-10-11 17:35:14', '2023-10-11', NULL, '2023-10-11 11:35:21', NULL),
(1230, 1, 1, '2023-10-11 17:35:16', '2023-10-11', NULL, '2023-10-11 11:35:21', NULL),
(1291, 1, 1, '2023-10-11 17:35:18', '2023-10-11', NULL, '2023-10-11 11:35:24', NULL),
(1353, 1, 1, '2023-10-11 17:37:07', '2023-10-11', NULL, '2023-10-11 11:37:14', NULL),
(1416, 1, 1, '2023-10-11 17:37:41', '2023-10-11', NULL, '2023-10-11 11:37:47', NULL),
(1480, 1, 1, '2023-10-11 17:38:45', '2023-10-11', NULL, '2023-10-11 11:38:50', NULL),
(1545, 1, 1, '2023-10-11 17:38:53', '2023-10-11', NULL, '2023-10-11 11:38:59', NULL),
(1611, 1, 1, '2023-10-16 13:49:13', '2023-10-16', NULL, '2023-10-16 07:58:07', NULL),
(1612, 1, 1, '2023-10-16 13:49:28', '2023-10-16', NULL, '2023-10-16 07:58:07', NULL),
(1647, 0, 1, '2024-02-27 14:48:00', '2024-02-27', 'Demo', '2024-02-27 02:52:28', '2024-02-27 02:52:28'),
(1648, 0, 5, '2024-02-27 14:48:00', '2024-02-27', 'Demo', '2024-02-27 02:52:28', '2024-02-27 02:52:28'),
(1649, 0, 3, '2024-02-27 14:48:00', '2024-02-27', 'Demo', '2024-02-27 02:52:28', '2024-02-27 02:52:28'),
(1675, 0, 1, '2024-02-27 10:00:00', '2024-02-27', 'Demo', '2024-02-27 05:01:45', '2024-02-27 05:01:45'),
(1676, 0, 5, '2024-02-27 10:00:00', '2024-02-27', 'Demo', '2024-02-27 05:01:45', '2024-02-27 05:01:45'),
(1677, 0, 3, '2024-02-27 10:00:00', '2024-02-27', 'Demo', '2024-02-27 05:01:45', '2024-02-27 05:01:45'),
(1678, 0, 6, '2024-02-27 10:00:00', '2024-02-27', 'Demo', '2024-02-27 05:01:46', '2024-02-27 05:01:46'),
(1679, 0, 1, '2024-02-27 09:00:00', '2024-02-27', 'Demo', '2024-02-27 05:16:28', '2024-02-27 05:16:28'),
(1680, 0, 1, '2024-02-27 17:16:00', '2024-02-27', 'Demo', '2024-02-27 05:16:28', '2024-02-27 05:16:28'),
(1681, 0, 5, '2024-02-27 09:00:00', '2024-02-27', 'Demo', '2024-02-27 05:16:29', '2024-02-27 05:16:29'),
(1682, 0, 5, '2024-02-27 17:16:00', '2024-02-27', 'Demo', '2024-02-27 05:16:29', '2024-02-27 05:16:29'),
(1683, 0, 3, '2024-02-27 09:00:00', '2024-02-27', 'Demo', '2024-02-27 05:16:29', '2024-02-27 05:16:29'),
(1684, 0, 3, '2024-02-27 17:16:00', '2024-02-27', 'Demo', '2024-02-27 05:16:29', '2024-02-27 05:16:29'),
(1685, 0, 6, '2024-02-27 09:00:00', '2024-02-27', 'Demo', '2024-02-27 05:16:29', '2024-02-27 05:16:29'),
(1686, 0, 6, '2024-02-27 17:16:00', '2024-02-27', 'Demo', '2024-02-27 05:16:29', '2024-02-27 05:16:29'),
(1687, 0, 1, '2024-02-27 16:16:00', '2024-02-27', 'Demo', '2024-02-27 05:16:55', '2024-02-27 05:16:55'),
(1688, 0, 5, '2024-02-27 16:16:00', '2024-02-27', 'Demo', '2024-02-27 05:16:55', '2024-02-27 05:16:55'),
(1689, 0, 3, '2024-02-27 16:16:00', '2024-02-27', 'Demo', '2024-02-27 05:16:55', '2024-02-27 05:16:55'),
(1690, 0, 6, '2024-02-27 16:16:00', '2024-02-27', 'Demo', '2024-02-27 05:16:55', '2024-02-27 05:16:55'),
(1691, 0, 1, '2024-03-05 08:54:00', '2024-03-05', 'Demo', '2024-03-04 23:54:28', '2024-03-04 23:54:28'),
(1692, 0, 1, '2024-03-05 12:40:00', '2024-03-05', 'Demo', '2024-03-06 00:41:03', '2024-03-06 00:41:03'),
(1693, 0, 1, '2024-03-05 12:43:00', '2024-03-05', 'Demo', '2024-03-06 00:44:11', '2024-03-06 00:44:11'),
(1694, 0, 1, '2024-03-06 09:30:00', '2024-03-06', 'Demo', '2024-03-06 03:30:16', '2024-03-06 03:30:16'),
(1695, 0, 1, '2024-03-06 15:30:00', '2024-03-06', 'Demo', '2024-03-06 03:30:16', '2024-03-06 03:30:16'),
(1700, 0, 6, '2024-03-06 09:30:00', '2024-03-06', 'Demo', '2024-03-06 03:30:16', '2024-03-06 03:30:16'),
(1701, 0, 6, '2024-03-06 15:30:00', '2024-03-06', 'Demo', '2024-03-06 03:30:16', '2024-03-06 03:30:16'),
(1702, 0, 1, '2024-03-20 09:30:00', '2024-03-20', 'Demo', '2024-03-21 00:23:36', '2024-03-21 00:23:36'),
(1703, 0, 1, '2024-03-21 18:23:00', '2024-03-21', 'Demo', '2024-03-21 00:23:36', '2024-03-21 00:23:36'),
(1704, 0, 5, '2024-03-20 09:30:00', '2024-03-20', 'Demo', '2024-03-21 00:23:36', '2024-03-21 00:23:36'),
(1705, 0, 5, '2024-03-21 18:23:00', '2024-03-21', 'Demo', '2024-03-21 00:23:36', '2024-03-21 00:23:36'),
(1706, 0, 3, '2024-03-20 09:30:00', '2024-03-20', 'Demo', '2024-03-21 00:23:36', '2024-03-21 00:23:36'),
(1707, 0, 3, '2024-03-21 18:23:00', '2024-03-21', 'Demo', '2024-03-21 00:23:36', '2024-03-21 00:23:36'),
(1708, 0, 6, '2024-03-20 09:30:00', '2024-03-20', 'Demo', '2024-03-21 00:23:36', '2024-03-21 00:23:36'),
(1709, 0, 6, '2024-03-21 18:23:00', '2024-03-21', 'Demo', '2024-03-21 00:23:36', '2024-03-21 00:23:36'),
(1710, 0, 1, '2024-03-19 10:30:00', '2024-03-19', 'Demo', '2024-03-21 00:24:00', '2024-03-21 00:24:00'),
(1711, 0, 1, '2024-03-21 18:23:00', '2024-03-21', 'Demo', '2024-03-21 00:24:00', '2024-03-21 00:24:00'),
(1712, 0, 5, '2024-03-19 10:30:00', '2024-03-19', 'Demo', '2024-03-21 00:24:00', '2024-03-21 00:24:00'),
(1713, 0, 5, '2024-03-21 18:23:00', '2024-03-21', 'Demo', '2024-03-21 00:24:00', '2024-03-21 00:24:00'),
(1714, 0, 3, '2024-03-19 10:30:00', '2024-03-19', 'Demo', '2024-03-21 00:24:00', '2024-03-21 00:24:00'),
(1715, 0, 3, '2024-03-21 18:23:00', '2024-03-21', 'Demo', '2024-03-21 00:24:00', '2024-03-21 00:24:00'),
(1716, 0, 6, '2024-03-19 10:30:00', '2024-03-19', 'Demo', '2024-03-21 00:24:00', '2024-03-21 00:24:00'),
(1717, 0, 6, '2024-03-21 18:23:00', '2024-03-21', 'Demo', '2024-03-21 00:24:00', '2024-03-21 00:24:00'),
(1718, 0, 1, '2024-03-21 10:15:00', '2024-03-21', 'Demo', '2024-03-21 00:26:16', '2024-03-21 00:26:16'),
(1719, 0, 5, '2024-03-21 10:15:00', '2024-03-21', 'Demo', '2024-03-21 00:26:16', '2024-03-21 00:26:16'),
(1720, 0, 3, '2024-03-21 10:15:00', '2024-03-21', 'Demo', '2024-03-21 00:26:16', '2024-03-21 00:26:16'),
(1721, 0, 6, '2024-03-21 10:15:00', '2024-03-21', 'Demo', '2024-03-21 00:26:16', '2024-03-21 00:26:16'),
(1722, 0, 1, '2024-03-24 08:53:00', '2024-03-24', 'Demo', '2024-03-24 00:53:48', '2024-03-24 00:53:48'),
(1723, 0, 2, '2024-03-24 08:53:00', '2024-03-24', 'Demo', '2024-03-24 00:53:48', '2024-03-24 00:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `type`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(4, 'The Story of Behind Our Resort', NULL, '<p>The Story of Behind Our Resort&nbsp;<label class=\"form-label required\" for=\"description\">Description</label></p>', 'images/blog/17-07-2025-15-08-41-Pirate-ship-2.jpg', 1, '2025-07-17 09:08:41', '2025-07-17 09:08:41'),
(5, 'Bangladesh Tourism Board conduct a training season at wonder park and eco Reassort.', NULL, '<section class=\"elementor-section elementor-top-section elementor-element elementor-element-913bf3e elementor-section-boxed elementor-section-height-default elementor-section-height-default\" data-element_type=\"section\" data-id=\"913bf3e\">\n<div class=\"elementor-container elementor-column-gap-default\">\n<div class=\"elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-c23ad7a\" data-element_type=\"column\" data-id=\"c23ad7a\">\n<div class=\"elementor-widget-wrap elementor-element-populated\">\n<div class=\"elementor-element elementor-element-a6d1ffc elementor-widget elementor-widget-text-editor\" data-element_type=\"widget\" data-id=\"a6d1ffc\" data-widget_type=\"text-editor.default\">\n<div class=\"elementor-widget-container\">\n<p>We are really happy to introduce today, October 8, 2023, a training program from the Bangladesh Tourism Board and the Ministry of Civil Aviation and Tourism at our Wonder Park and Eco Resort Morjal, Raipura, Narshingdi, for our office staff and support service section. This program aims to enhance our employees&rsquo; knowledge and skills in the tourism industry, allowing them to provide exceptional service to our valued guests. We believe that this training program will not only benefit our staff but also contribute to the overall growth and development of the tourism sector in Bangladesh.</p>\n\n<p>Summary of the training:</p>\n\n<ol>\n	<li>The significance of training programs in the tourism industry and their impact on enhancing employees&rsquo; knowledge and skills</li>\n	<li>The benefits of providing exceptional service to guests in the tourism sector and its importance for attracting more visitors.</li>\n	<li>The role of Wonder Park and Eco Resort Morjal in promoting tourism in Narshingdi, Bangladesh, and how this training program contributes to its development.</li>\n	<li>Exploring the potential growth opportunities for the tourism sector in Bangladesh with a focus on employee training and skill enhancement to meet the demands of a growing industry.</li>\n	<li>The importance of continuous learning and professional development in the tourism sector to stay competitive in a global market</li>\n</ol>\n\n<p>The program focuses on enhancing the skills of residents to work in various roles within the tourism sector, such as tour guides, hotel staff, and customer service representatives. By providing these training opportunities, Wonder Park and Eco Resort Morjal not only contribute to the development of tourism in Narshingdi but also create employment opportunities for the community.</p>\n\n<p>Additionally,&nbsp; continuous learning and professional development in the tourism sector help improve the overall quality of services provided to tourists. This, in turn, enhances the reputation of Narshingdi as a tourist destination and attracts more visitors. Furthermore, the program also promotes sustainable tourism practices, ensuring that the local community and environment are not negatively impacted by the influx of tourists. Overall, the commitment to continuous learning and professional development in the tourism sector is crucial for the long-term success and growth of both Wonder Park and Eco Resort Morjal, as well as the entire tourism industry in Narshingdi.</p>\n\n<p>I hope that our collaboration will continue in the future for all skill-building and knowledge-sharing projects. We can build a successful tourism economy that benefits not just the businesses involved but also the neighborhood and environment by cooperating and helping one another. Together, let&rsquo;s make Narshingdi a viable and appealing travel destination for visitors from all over the world.</p>\n</div>\n</div>\n</div>\n</div>\n</div>\n</section>', 'images/blog/17-07-2025-15-48-24-Mickey-Train.jpg', 1, '2025-07-17 09:48:24', '2025-07-17 09:48:24'),
(6, 'Wonder Park and Eco Resort for your holy days', NULL, '<section class=\"elementor-section elementor-top-section elementor-element elementor-element-65a4753 elementor-section-boxed elementor-section-height-default elementor-section-height-default\" data-element_type=\"section\" data-id=\"65a4753\">\r\n<div class=\"elementor-container elementor-column-gap-default\">\r\n<div class=\"elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-740d05a\" data-element_type=\"column\" data-id=\"740d05a\">\r\n<div class=\"elementor-widget-wrap elementor-element-populated\">\r\n<div class=\"elementor-element elementor-element-89b7f15 elementor-widget elementor-widget-text-editor\" data-element_type=\"widget\" data-id=\"89b7f15\" data-widget_type=\"text-editor.default\">\r\n<div class=\"elementor-widget-container\">\r\n<h3>1. The concept of combining theme park entertainment with an eco-friendly resort experience:</h3>\r\n\r\n<p>It is a unique and exciting idea that is sure to provide an unforgettable vacation. With attractions ranging from thrilling metro rail rides to soothing nature walks, this park and eco-resort have something for everyone. The accommodations are designed to blend seamlessly with the natural surroundings, providing a peaceful and serene atmosphere. Guests can enjoy delicious organic meals made from locally sourced ingredients at the on-site restaurant. The park offers a variety of activities for all ages. The park also has a strong commitment to sustainability, including using renewable energy sources and implementing recycling programs. With its unique combination of entertainment and eco-friendliness, this park and eco-resort is the perfect destination for your next holy day vacation. On nature trails, there is something for everyone to enjoy. The resort itself boasts luxurious accommodations that are designed with sustainability in mind, utilizing renewable energy sources and eco-friendly materials. And with delicious farm-to-table dining options available, guests can indulge in delicious cuisine while supporting local farmers and minimizing their carbon footprint. Whether you&rsquo;re looking for a family-friendly getaway or a romantic retreat, this park and eco-resort is the perfect destination for your next holy day vacation.</p>\r\n\r\n<h3>2. A review or analysis of the Wonder Park and Eco Resort&rsquo;s amenities, attractions, and sustainability efforts</h3>\r\n\r\n<p>The Wonder Park and Eco Resort offers a unique vacation experience that combines relaxation, adventure, and environmental responsibility. The resort&rsquo;s amenities are top-notch, with comfortable accommodations and a variety of recreational activities. For families with children, the park&rsquo;s attractions are sure to delight, with thrilling rides and interactive exhibits that showcase the wonders of nature. Couples seeking a romantic getaway will appreciate the resort&rsquo;s secluded location and intimate atmosphere. Above all, Wonder Park and Eco Resort are dedicated to sustainability efforts that preserve the natural beauty of its surroundings. From energy-efficient buildings to recycling programs, every aspect of the resort is designed to minimize its impact on the environment. By choosing this eco-friendly destination for your next vacation, you can enjoy all the benefits of a luxurious getaway while doing your part to protect our planet.</p>\r\n\r\n<h3>3. How does this destination fit into larger trends in sustainable tourism and eco-tourism around the country?</h3>\r\n\r\n<p>The Wonder Park and Eco Resort are part of a larger trend in sustainable tourism and eco-tourism around the country. More and more travelers are seeking out destinations that prioritize environmental responsibility and conservation. These eco-conscious travelers want to enjoy the beauty of nature without contributing to its destruction. As a result, many resorts, hotels, and tour companies are adopting sustainable practices and offering eco-friendly experiences. The Wonder Park and Eco Resort is a prime example of this trend, with its commitment to sustainability and focus on providing a unique, environmentally responsible vacation experience.</p>\r\n\r\n<h3>4. Personal experiences or stories from travelers who have visited the Wonder Park and Eco Resort during their holidays are a testament to the success of this eco-friendly approach.</h3>\r\n\r\n<p>Many have praised the resort&rsquo;s use of renewable energy sources, such as instant generators and wind turbines, which provide power for the entire property. The resort also features organic gardens that supply fresh produce for its restaurants, reducing the need for transportation and packaging. In addition, guests can participate in activities such as nature hikes and wildlife observation tours, which allow them to appreciate the natural beauty of the surrounding area while learning about conservation efforts. The Wonder Park and Eco Resort&rsquo;s commitment to sustainability extends beyond its physical operations; it also supports local communities through partnerships with local businesses and organizations. Overall, this eco-friendly resort provides a unique vacation experience that not only benefits the environment but also enriches the lives of its guests.</p>\r\n\r\n<h3>5. An exploration of the benefits (and potential drawbacks) of choosing a holiday destination that prioritizes:</h3>\r\n\r\n<div style=\"border-left: 4.5pt solid #cccccc; padding: 0in 0in 0in 16pt;\">\r\n<p>Sustainable tourism can have a positive impact on the environment and local communities, but it can also be challenging due to limited availability and higher costs. However, as more people become aware of the importance of sustainable tourism, more options will be available.</p>\r\n</div>\r\n\r\n<p>Families visiting the Wonder Park and Eco Resort can enjoy a wide range of sustainable activities that are both educational and fun. Kids can spend time in the resort&rsquo;s Kids Zone, which is designed to teach children about the environment and conservation efforts. They can also participate in nature hikes and wildlife observation tours, which allow them to explore the natural beauty of the surrounding area. The resort&rsquo;s commitment to sustainability extends beyond its activities and operations; it also supports local communities through partnerships with local businesses and organizations. Overall, families who choose this eco-friendly resort will not only have a great vacation but will also be making a positive impact on the environment.</p>\r\n\r\n<div style=\"border-left: 4.5pt solid #cccccc; padding: 0in 0in 0in 16pt;\">\r\n<p>Wonder Park and Eco Resort can also take advantage of the rides available. The resort offers a variety of exciting rides that are sure to thrill visitors of all ages. From the pirate ship, Micky Train, swing chair, wheelchair, and water boat, there is something for everyone at this eco-friendly destination. And the best part? All of the rides are designed with sustainability in mind, so families can enjoy their vacation without worrying about harming the environment.</p>\r\n</div>\r\n\r\n<p>In addition to the exciting rides, Wonder Park and Eco Resort provide a comfortable seating area where families can relax and enjoy the beautiful surroundings. The seating area is designed with eco-friendly materials and is strategically placed to provide stunning views of the resort&rsquo;s lush greenery. Families can unwind after a long day of activities and take in the natural beauty of the area while feeling good about their sustainable vacation choice.</p>\r\n\r\n<p>For those seeking a moment of spiritual reflection, Wonder Park and Eco Resort also offer a designated prayer zone. This peaceful area provides a serene environment where visitors can connect with their faith and find inner peace amid the natural beauty. The prayer zone is thoughtfully designed to blend seamlessly with the surrounding environment, creating a harmonious space where visitors can feel at ease. Whether you&rsquo;re seeking adventure or simply looking for a serene getaway, Wonder Park and Eco Resort have something for everyone.</p>\r\n\r\n<p>One of the highlights of Wonder Park and Eco Resort is the beautiful sculpture on display. It&rsquo;s a stunning piece of art that captures the essence of the natural beauty surrounding the resort. The sculpture is made from sustainable materials, ensuring that it aligns with the resort&rsquo;s commitment to environmental conservation. Visitors can admire the sculpture while taking in the breathtaking views from the comfortable seating area nearby. It&rsquo;s a perfect spot for families to relax and appreciate both art and nature in one place.</p>\r\n\r\n<p>One of the most enchanting sculptures on display at Wonder Park and Eco Resort is the fairy sculpture, which perfectly showcases the beauty of nature. The artist has crafted a stunning depiction of a fairy that seems to come to life amid the serene surroundings. The intricate details and delicate features of the sculpture make it a must-see for anyone interested in art or nature. With its graceful lines and ethereal presence, this sculpture captures the essence of beauty in a way that will leave visitors feeling inspired and enchanted.</p>\r\n\r\n<p>The lakeside setting area at Wonder Park and Eco Resort features a magnificent statue that perfectly blends with the natural beauty of the surroundings. The sculpture is not only visually appealing but also eco-friendly, reflecting the resort&rsquo;s commitment to environmental preservation. Visitors can appreciate the artwork while enjoying the serene views from the comfortable seating area nearby. It&rsquo;s an ideal location for families to unwind and experience art and nature in perfect harmony.</p>\r\n\r\n<p>Visitors to Wonder Park and Eco Resort can enjoy the beauty of nature in more ways than one. The resort offers a Gitter Shower, which is a unique way to refresh and rejuvenate while surrounded by stunning scenery and a green environment. For those looking for a more playful experience, the Dolphin Shower is also available, providing a fun and interactive way to cool off during a hot day. Both options allow guests to appreciate the natural beauty of their surroundings while enjoying some much-needed relaxation.</p>\r\n\r\n<p>One of the standout features of Wonder Park and Eco Resort is the stunning sculpture located in the lakeside setting area. The sculpture incorporates elements of a dragon, penguin, and bird to create a truly unique and visually appealing piece. Not only does it add to the natural beauty of the surroundings, but it also reflects the resort&rsquo;s commitment to eco-friendly practices. Visitors can relax nearby and take in the artwork while enjoying serene views. In addition to admiring art, guests can also refresh themselves with unique shower experiences that allow them to appreciate nature in a fun, interactive way.</p>\r\n\r\n<p><b>Mango Litchi Garden and various flower tree gardens are eco-friendly signatures.</b></p>\r\n\r\n<p>Wonder Park and Eco Resort. These gardens are not only beautiful to look at, but they also serve as a natural habitat for various species of birds and insects. The resort takes great pride in its efforts to preserve the environment and promote sustainable tourism. Guests can take guided tours of the gardens to learn more about the different plants and their importance in the ecosystem. The gardens also provide a peaceful setting for guests to unwind and connect with nature. With so many eco-friendly features and activities, Wonder Park and Eco Resort is the perfect destination for those looking to escape the hustle and bustle of city life and immerse themselves in a serene natural environment. Whether you are an art enthusiast or a nature lover, this resort has something for everyone. So, pack your bags and get ready for an unforgettable experience at Wonder Park and Eco Resort.</p>\r\n\r\n<p>The Wonder Park and Eco Resort is an ideal picnic and event venue, with its lush gardens providing a peaceful setting to unwind and connect with nature. Guests can take guided tours of the gardens to learn about the different plants and their role in the ecosystem. The resort&rsquo;s commitment to sustainable tourism also ensures that visitors can enjoy a serene natural environment while minimizing their impact on the environment. Whether you&rsquo;re looking for an art-filled getaway or a chance to immerse yourself in nature, Wonder Park and Eco Resort have something for everyone. So pack your bags and head to this idyllic destination for an unforgettable experience.</p>\r\n\r\n<p>In addition to being a great vacation spot, Wonder Park and Eco Resort is also an excellent venue for corporate events and team-building activities. With its eco-friendly features and serene natural environment, it provides the perfect backdrop for employees to unwind and connect. The resort offers a range of activities such as hiking, birdwatching, and group yoga sessions that can be incorporated into a day-long program for corporate groups. So why not book your next corporate event at Wonder Park and Eco Resort? It&rsquo;s sure to be a productive and refreshing experience for all.</p>\r\n\r\n<p>A green-sided walkway leads guests through lush gardens and past tranquil water features, creating a serene atmosphere that is perfect for team-building exercises and brainstorming sessions. The resort also offers a variety of meeting spaces equipped with state-of-the-art technology, ensuring that your business needs are met. In addition to the outdoor activities, Wonder Park and Eco Resort boast a top-rated restaurant that serves locally sourced, organic cuisine. After a day of work and play, guests can retire to their luxurious accommodations, complete with breathtaking views. So why settle for a stuffy conference room when you can treat your team to an unforgettable experience at Wonder Park and Eco Resort? Book now and see the difference it can make for your business.</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</section>', 'images/blog/17-07-2025-16-29-16-wonder-park-entry-gate.jpg', 1, '2025-07-17 10:29:16', '2025-07-17 10:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_no` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `total_days` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `payment_status` tinyint(4) DEFAULT 0,
  `Booking_status` varchar(50) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookingservices`
--

CREATE TABLE `bookingservices` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `break_times`
--

CREATE TABLE `break_times` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `break_times`
--

INSERT INTO `break_times` (`id`, `start_time`, `end_time`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '13:00:00', '14:00:00', 'Demo', '2024-01-11 00:27:03', '2024-01-11 00:27:03');

-- --------------------------------------------------------

--
-- Table structure for table `checkincheckout`
--

CREATE TABLE `checkincheckout` (
  `LogID` int(11) NOT NULL,
  `BookingID` int(11) NOT NULL,
  `CheckInTime` datetime DEFAULT NULL,
  `CheckOutTime` datetime DEFAULT NULL,
  `Status` enum('CheckedIn','CheckedOut') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `type` tinyint(4) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `employee_id`, `title`, `amount`, `percentage`, `type`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Commission Title', 3000.00, '5.00', 2, 1, '2023-07-13 08:57:29', '2023-07-19 11:49:28'),
(2, 1, 'Commission Title', 5000.00, '0.00', 1, 1, '2023-07-19 11:36:53', '2023-07-19 11:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_details` varchar(255) DEFAULT NULL,
  `proprietor_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `factory_address` text DEFAULT NULL,
  `company_mobile` varchar(255) DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `fb_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `linkdin_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `trade_license` varchar(100) DEFAULT NULL,
  `tin_no` varchar(100) DEFAULT NULL,
  `bin_no` varchar(100) DEFAULT NULL,
  `vat_no` varchar(100) DEFAULT NULL,
  `vat_rate` int(11) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `water_mark` varchar(255) DEFAULT NULL,
  `company_logo_one` varchar(255) DEFAULT NULL,
  `company_logo_two` varchar(255) DEFAULT NULL,
  `company_footer_logo` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `company_name`, `company_details`, `proprietor_name`, `company_address`, `factory_address`, `company_mobile`, `company_phone`, `company_email`, `website_url`, `youtube_url`, `fb_url`, `twitter_url`, `linkdin_url`, `instagram_url`, `trade_license`, `tin_no`, `bin_no`, `vat_no`, `vat_rate`, `currency`, `water_mark`, `company_logo_one`, `company_logo_two`, `company_footer_logo`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Wonder Park & Eco Resort', 'Wonder Park & Eco Resort', 'Perfume Chemical Industries PLC', 'Marjal,Raipura,Narsingdi, Dhaka Division,Bangladesh', 'Marjal, Raipura, Narsingdi, Dhaka Division, Bangladesh', '01511-500080', NULL, 'software@nrbtelecom.com', NULL, 'https://www.youtube.com/@wonderparkecoresort', NULL, NULL, NULL, NULL, '000-000', '0', '200-200', NULL, NULL, 'BDT', NULL, 'public/images/company/02-07-2025-15-43-16_Untitled-1.png', 'public/images/company/02-07-2025-15-43-16_wonderpark_logo.png', NULL, 1, '2024-01-22 05:30:36', '2025-08-06 09:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_type` int(11) DEFAULT NULL,
  `ac_id` int(10) DEFAULT NULL,
  `customer_code` varchar(150) DEFAULT NULL,
  `customer_name` varchar(150) DEFAULT NULL,
  `customer_mobile` varchar(100) DEFAULT NULL,
  `customer_email` varchar(50) DEFAULT NULL,
  `customer_address` longtext DEFAULT NULL,
  `shipping_address` longtext DEFAULT NULL,
  `customer_gender` int(11) DEFAULT 0,
  `customer_DOB` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `nid_number` varchar(100) DEFAULT NULL,
  `vat_reg_no` varchar(100) DEFAULT NULL,
  `tin_no` varchar(100) DEFAULT NULL,
  `trade_license` varchar(100) DEFAULT NULL,
  `discount_rate` decimal(18,2) DEFAULT 0.00,
  `security_deposit` decimal(18,2) DEFAULT 0.00,
  `credit_limit` decimal(18,2) DEFAULT 0.00,
  `customer_area` varchar(150) DEFAULT NULL,
  `shipping_contact` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `done_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_type`, `ac_id`, `customer_code`, `customer_name`, `customer_mobile`, `customer_email`, `customer_address`, `shipping_address`, `customer_gender`, `customer_DOB`, `image`, `nid_number`, `vat_reg_no`, `tin_no`, `trade_license`, `discount_rate`, `security_deposit`, `credit_limit`, `customer_area`, `shipping_contact`, `status`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'Reza', '01812328926', NULL, 'Badda, Dhaka', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 1, NULL, '2025-02-05 11:20:08', '2025-02-06 11:12:29'),
(2, 1, NULL, NULL, 'Rana', '01711235894', NULL, 'Badda, Dhaka12', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 1, NULL, '2025-02-05 11:25:36', '2025-02-06 11:12:19'),
(3, 1, NULL, NULL, 'Imran', '4532453453', NULL, 'Dhaka, Bangladesh', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 1, NULL, '2025-02-05 12:18:20', '2025-02-06 11:12:04'),
(4, 1, NULL, NULL, 'Masud', '77777777777', NULL, 'M Pur', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 1, NULL, '2025-02-06 09:48:28', '2025-02-06 11:11:56'),
(5, 1, NULL, NULL, 'D M Reza', '01913865989', NULL, 'Gulshan-1, Dhaka, Bangladesh', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 1, NULL, '2025-02-06 12:32:24', '2025-02-09 04:52:22'),
(6, 1, NULL, NULL, 'Imran g', '5465465', NULL, 'Badda, Dhaka', NULL, 0, NULL, NULL, '45646', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-02-09 05:25:57', '2025-02-09 05:25:57'),
(7, 1, NULL, NULL, 'Reza', '01812328926', NULL, 'Badda, Dhaka', NULL, 0, NULL, NULL, '5555555555', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-02-09 07:48:49', '2025-02-09 07:48:49'),
(8, 1, NULL, NULL, 'Masud', '016000001', NULL, 'Dhaka, Bangladesh', NULL, 0, NULL, NULL, '789412563', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-02-09 09:06:30', '2025-02-09 09:06:30'),
(9, 1, NULL, NULL, 'Reza', '77777777777', NULL, 'M Pur', NULL, 0, NULL, NULL, '789412563', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-02-09 09:16:03', '2025-02-09 09:16:03'),
(10, 1, NULL, NULL, 'Raihan', '4532453453', NULL, 'Dhaka, Bangladesh', NULL, 0, NULL, NULL, '521545876', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-02-09 09:44:33', '2025-02-09 09:44:33'),
(11, 1, NULL, NULL, 'Kazi Jahidul Haque', '0175412125', NULL, '3 No Road', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-02-09 10:37:18', '2025-02-09 10:37:18'),
(12, 1, NULL, NULL, 'Reza', '01812328926', NULL, 'Badda, Dhaka', NULL, 0, NULL, NULL, '5444444', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-02-10 05:28:09', '2025-02-10 05:28:09'),
(13, 1, NULL, NULL, 'Reza', '014556445', NULL, 'Dhaka', NULL, 0, NULL, NULL, '41546456', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-05-26 11:38:27', '2025-05-26 11:38:27'),
(14, 1, NULL, NULL, 'Reza', '01254345354', NULL, 'Dhaka', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-22 07:58:10', '2025-07-22 07:58:10'),
(15, 1, NULL, NULL, 'Reza 456', '012543455436', NULL, 'Dhaka', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-22 12:22:01', '2025-07-22 12:22:01'),
(16, 1, NULL, NULL, 'Reza 789', '6555656652', NULL, 'Dhaka', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-23 07:20:31', '2025-07-23 07:20:31'),
(17, 1, NULL, NULL, 'Reza 7892', '014556445', NULL, 'Dhaka', NULL, 0, NULL, NULL, '41546456', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 06:09:18', '2025-07-24 06:09:18'),
(18, 1, NULL, NULL, 'Reza ghghhf', '5435445654', NULL, 'Dhaka', NULL, 0, NULL, NULL, '5676576', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 06:17:11', '2025-07-24 06:17:11'),
(19, 1, NULL, NULL, 'Reza 123', '5435445654', NULL, 'Dhaka', NULL, 0, NULL, NULL, '5676576', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 07:53:01', '2025-07-24 07:53:01'),
(20, 1, NULL, NULL, 'tom', '5235434534', NULL, 'Dhaka', NULL, 0, NULL, NULL, '657676', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 09:03:49', '2025-07-24 09:03:49'),
(21, 1, NULL, NULL, 'Reza 789', '5435445654', NULL, 'Dhaka', NULL, 0, NULL, NULL, '5676576', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 09:37:15', '2025-07-24 09:37:15'),
(22, 1, NULL, NULL, 'Reza 7777', '5235434534', NULL, 'Dhaka', NULL, 0, NULL, NULL, '657676', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 09:43:02', '2025-07-24 09:43:02'),
(23, 1, NULL, NULL, 'Rony', '0213456789', NULL, 'Dhaka', NULL, 0, NULL, NULL, '9159519', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 09:49:30', '2025-07-24 09:49:30'),
(24, 1, NULL, NULL, 'Reza 7777', '0213456789', NULL, 'Dhaka', NULL, 0, NULL, NULL, '9159519', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 10:09:49', '2025-07-24 10:09:49'),
(25, 1, NULL, NULL, 'Reza 789', '5235434534', NULL, 'Dhaka', NULL, 0, NULL, NULL, '657676', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 10:14:52', '2025-07-24 10:14:52'),
(26, 1, NULL, NULL, 'Reza 7777', '5435445654', NULL, 'Dhaka', NULL, 0, NULL, NULL, '5676576', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 10:36:04', '2025-07-24 10:36:04'),
(27, 1, NULL, NULL, 'tom', '0171125483', NULL, 'Dhaka', NULL, 0, NULL, NULL, '5676576', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 10:59:17', '2025-07-24 10:59:17'),
(28, 1, NULL, NULL, 'tom', '0213456789', NULL, 'Dhaka', NULL, 0, NULL, NULL, '9159519', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 11:02:15', '2025-07-24 11:02:15'),
(29, 1, NULL, NULL, 'Reza 789', '655565665', NULL, 'Dhaka', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, 0, NULL, '2025-07-24 12:41:37', '2025-07-24 12:41:37');

-- --------------------------------------------------------

--
-- Table structure for table `customer_ledgers`
--

CREATE TABLE `customer_ledgers` (
  `id` int(10) UNSIGNED NOT NULL,
  `ledger_date` date NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `debit` decimal(18,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(18,2) NOT NULL DEFAULT 0.00,
  `payment_type` varchar(50) DEFAULT NULL,
  `bank_name` varchar(50) DEFAULT NULL,
  `cheque_no` varchar(50) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `card_no` varchar(50) DEFAULT NULL,
  `bkash_merchant_number` varchar(50) DEFAULT NULL,
  `bkash_payment_number` varchar(50) DEFAULT NULL,
  `bkash_trx_id` varchar(50) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `is_previous_due` tinyint(4) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `done_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE `customer_types` (
  `id` int(10) NOT NULL,
  `type_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_types`
--

INSERT INTO `customer_types` (`id`, `type_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'General Customer', 1, '2024-05-14 09:39:31', '2024-12-22 09:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `damage_products`
--

CREATE TABLE `damage_products` (
  `id` int(11) NOT NULL,
  `damage_no` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `purchasePrice` decimal(10,2) DEFAULT NULL,
  `salePrice` decimal(10,2) DEFAULT NULL,
  `damage_quantity` int(11) DEFAULT NULL,
  `damage_reason` varchar(500) DEFAULT NULL,
  `damage_date` date DEFAULT NULL,
  `is_exchangeable` tinyint(1) DEFAULT 0,
  `is_repairable` tinyint(1) DEFAULT 0,
  `is_resaleable` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT NULL,
  `done_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `damage_products`
--

INSERT INTO `damage_products` (`id`, `damage_no`, `product_id`, `warehouse_id`, `supplier_id`, `purchasePrice`, `salePrice`, `damage_quantity`, `damage_reason`, `damage_date`, `is_exchangeable`, `is_repairable`, `is_resaleable`, `status`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 'DAM000001', 3, 1, 211, '700.00', '1000.00', 2, 'Damage', '2024-12-04', 1, 1, 1, 1, 'Demo', '2024-12-04 12:58:15', '2024-12-05 11:57:04'),
(2, 'DAM000001', 4, 1, 211, '40.00', '50.00', 1, 'Damage', '2024-12-04', 1, 1, 1, 1, 'Demo', '2024-12-04 12:58:15', '2024-12-05 11:57:08'),
(3, 'DAM000001', 4, 2, 212, '40.00', '50.00', 1, NULL, '2024-12-05', 1, NULL, NULL, 1, 'Demo', '2024-12-05 10:04:29', '2024-12-05 10:08:08'),
(4, 'DAM000002', 2, 2, 212, '250.00', '300.00', 1, 'Damage', '2024-12-05', 1, 1, 1, 1, 'Demo', '2024-12-05 10:09:50', '2024-12-05 10:10:38'),
(5, 'DAM000003', 5, 3, 213, '480.00', '590.00', 1, NULL, '2024-12-05', 1, 1, 1, 1, 'Demo', '2024-12-05 10:12:27', '2024-12-05 10:12:27'),
(6, 'DAM000003', 4, 3, 213, '40.00', '50.00', 1, NULL, '2024-12-05', 1, 1, 1, 1, 'Demo', '2024-12-05 10:12:27', '2024-12-05 10:12:27'),
(7, 'DAM000004', 3, 1, 212, '700.00', '1000.00', 1, 'Damage 2233', '2024-12-09', 1, NULL, 1, 1, 'Demo', '2024-12-09 12:44:44', '2024-12-09 12:44:44'),
(8, 'DAM000004', 5, 1, 212, '480.00', '590.00', 1, 'Damage 2233', '2024-12-09', 1, NULL, 1, 1, 'Demo', '2024-12-09 12:44:44', '2024-12-09 12:44:44'),
(9, 'DAM000005', 4, 2, 211, '40.00', '50.00', 1, 'Damage 32332324', '2024-12-09', 1, NULL, NULL, 1, 'Demo', '2024-12-09 12:49:49', '2024-12-09 12:49:49');

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `id` bigint(20) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `deduction_head` int(11) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `type` tinyint(4) DEFAULT 1,
  `created_by` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deduction_heads`
--

CREATE TABLE `deduction_heads` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `deduction_heads`
--

INSERT INTO `deduction_heads` (`id`, `name`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'TAX', 1, 1, '2023-07-13 07:21:21', '2023-12-17 05:14:33'),
(2, 'P/F', 1, 1, '2023-07-13 07:21:30', '2023-12-17 05:14:44'),
(0, 'Absent', 1, 1, '2023-12-17 04:55:26', '2023-12-17 05:16:54'),
(1, 'TAX', 1, 1, '2023-07-13 07:21:21', '2023-12-17 05:14:33'),
(2, 'P/F', 1, 1, '2023-07-13 07:21:30', '2023-12-17 05:14:44'),
(0, 'Absent', 1, 1, '2023-12-17 04:55:26', '2023-12-17 05:16:54'),
(1, 'TAX', 1, 1, '2023-07-13 07:21:21', '2023-12-17 05:14:33'),
(2, 'P/F', 1, 1, '2023-07-13 07:21:30', '2023-12-17 05:14:44'),
(0, 'Absent', 1, 1, '2023-12-17 04:55:26', '2023-12-17 05:16:54');

-- --------------------------------------------------------

--
-- Table structure for table `deduction_options`
--

CREATE TABLE `deduction_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `employee_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `employee_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `nid` int(20) DEFAULT NULL,
  `birth_id` int(20) DEFAULT NULL,
  `blood_group` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `marital_status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `spouse_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `religion` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `mobile` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `mobile_two` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `emergency_contact_person` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `emergency_contact_number` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `father_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `mother_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `present_address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `permanent_address` varchar(350) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `highest_education` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `job_status` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `quite_date` date DEFAULT NULL,
  `quite_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `signature` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `order` int(11) DEFAULT 1,
  `status` int(11) DEFAULT 1,
  `salary_held_up` int(11) DEFAULT 0,
  `attendance_bonus` int(11) DEFAULT 0,
  `bus_using` int(10) DEFAULT 0,
  `provident_fund` int(10) DEFAULT 0,
  `income_tax` int(10) DEFAULT 0,
  `mobile_allowance` int(10) DEFAULT 0,
  `over_time` int(10) DEFAULT 0,
  `created_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `posting_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `email`, `employee_code`, `employee_name`, `gender`, `dob`, `nid`, `birth_id`, `blood_group`, `marital_status`, `spouse_name`, `religion`, `mobile`, `mobile_two`, `emergency_contact_person`, `emergency_contact_number`, `father_name`, `mother_name`, `present_address`, `permanent_address`, `highest_education`, `job_status`, `quite_date`, `quite_type`, `photo`, `signature`, `order`, `status`, `salary_held_up`, `attendance_bonus`, `bus_using`, `provident_fund`, `income_tax`, `mobile_allowance`, `over_time`, `created_by`, `posting_date`, `created_at`, `updated_at`) VALUES
(38, NULL, NULL, '000001', 'Employee 1', 'Female', '2024-02-01', 654554, 5641541, 'A+', 'Married', 'Spouse', 'Islam', NULL, NULL, NULL, NULL, 'Father Name', 'Mother Name', 'Present Address', 'Permanent Address', 'Highest Education', 'Provisional', '2024-02-15', '2', 'public/images/employee/14-03-2024-06-57-23_demo-profile.jpg', 'public/images/employee/14-03-2024-06-57-23_signature.png', 1, 1, 0, 0, 0, 0, 0, 0, 0, 'Demo', '2024-02-01', '2024-02-14 01:39:40', '2024-05-30 06:13:39'),
(39, NULL, NULL, '000005', 'Employee 5', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Permanent', NULL, NULL, 'public/images/employee/14-03-2024-06-58-02_demo-profile.jpg', 'public/images/employee/14-03-2024-06-58-02_signature.png', 1, 1, 0, 0, 0, 0, 0, 0, 0, 'Demo', '2024-02-01', '2024-02-14 01:49:34', '2024-03-14 00:58:02'),
(40, NULL, NULL, '000003', 'Employee 3', 'Female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Permanent', NULL, NULL, 'public/images/employee/14-03-2024-06-58-18_demo-profile.jpg', 'public/images/employee/14-03-2024-06-58-18_signature.png', 1, 1, 0, 0, 0, 0, 0, 0, 0, 'Demo', '2024-02-01', '2024-02-14 03:19:34', '2024-03-25 00:06:59'),
(41, NULL, NULL, '000006', 'Employee 6', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Permanent', NULL, NULL, 'public/images/employee/14-03-2024-06-58-34_demo-profile.jpg', 'public/images/employee/14-03-2024-06-58-34_signature.png', 1, 1, 0, 0, 0, 0, 0, 0, 0, 'Demo', '2024-02-01', '2024-02-14 03:23:48', '2024-03-14 00:58:34'),
(43, NULL, NULL, '000002', 'Employee 2', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Permanent', NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, 0, 0, 'Demo', '2024-03-01', '2024-03-23 23:53:43', '2024-03-23 23:53:43'),
(44, NULL, NULL, '000007', 'Employee 7', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Permanent', NULL, NULL, NULL, NULL, 1, 1, 1, 0, 0, 0, 0, 0, 0, 'Demo', '2024-03-01', '2024-03-24 00:08:44', '2024-04-17 02:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `employees_backup`
--

CREATE TABLE `employees_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `employee_code` varchar(50) DEFAULT NULL,
  `employee_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `marital_status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `nid` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `mobile_two` varchar(20) DEFAULT NULL,
  `emergency_contact_person` varchar(100) DEFAULT NULL,
  `emergency_contact_number` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `present_address` varchar(250) DEFAULT NULL,
  `permanent_address` varchar(350) DEFAULT NULL,
  `job_status` varchar(50) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `order` int(11) DEFAULT 1,
  `status` int(11) DEFAULT 1,
  `photo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `signature` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `highest_education` varchar(100) DEFAULT NULL,
  `reference_one_name` varchar(300) DEFAULT NULL,
  `reference_one_phone` varchar(150) DEFAULT NULL,
  `reference_one_address` varchar(300) DEFAULT NULL,
  `reference_two_name` varchar(300) DEFAULT NULL,
  `reference_two_phone` varchar(150) DEFAULT NULL,
  `reference_two_address` varchar(300) DEFAULT NULL,
  `created_by` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employees_backup`
--

INSERT INTO `employees_backup` (`id`, `user_id`, `branch_id`, `department_id`, `designation_id`, `employee_code`, `employee_name`, `email`, `dob`, `gender`, `blood_group`, `marital_status`, `religion`, `nid`, `mobile`, `mobile_two`, `emergency_contact_person`, `emergency_contact_number`, `father_name`, `mother_name`, `present_address`, `permanent_address`, `job_status`, `joining_date`, `order`, `status`, `photo`, `signature`, `highest_education`, `reference_one_name`, `reference_one_phone`, `reference_one_address`, `reference_two_name`, `reference_two_phone`, `reference_two_address`, `created_by`, `created_at`, `updated_at`) VALUES
(16, 45, 1, 1, 2, '2023002', 'Robiul', 'e@demo.com', '2023-01-04', 'Male', 'A+', 'Married', 'Islam', '6556654645456654', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Permanent', '2023-12-04', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-12-04 05:08:06', '2023-12-04 05:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `employees_update`
--

CREATE TABLE `employees_update` (
  `id` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `employee_code` varchar(50) DEFAULT NULL,
  `employee_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `marital_status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `nid` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `mobile_two` varchar(20) DEFAULT NULL,
  `emergency_contact_person` varchar(100) DEFAULT NULL,
  `emergency_contact_number` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `present_address` varchar(250) DEFAULT NULL,
  `permanent_address` varchar(350) DEFAULT NULL,
  `job_status` varchar(50) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `order` int(11) DEFAULT 1,
  `status` int(11) DEFAULT 1,
  `photo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `signature` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `highest_education` varchar(100) DEFAULT NULL,
  `reference_one_name` varchar(300) DEFAULT NULL,
  `reference_one_phone` varchar(150) DEFAULT NULL,
  `reference_one_address` varchar(300) DEFAULT NULL,
  `reference_two_name` varchar(300) DEFAULT NULL,
  `reference_two_phone` varchar(150) DEFAULT NULL,
  `reference_two_address` varchar(300) DEFAULT NULL,
  `created_by` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employees_update`
--

INSERT INTO `employees_update` (`id`, `user_id`, `branch_id`, `department_id`, `designation_id`, `employee_code`, `employee_name`, `email`, `dob`, `gender`, `blood_group`, `marital_status`, `religion`, `nid`, `mobile`, `mobile_two`, `emergency_contact_person`, `emergency_contact_number`, `father_name`, `mother_name`, `present_address`, `permanent_address`, `job_status`, `joining_date`, `order`, `status`, `photo`, `signature`, `highest_education`, `reference_one_name`, `reference_one_phone`, `reference_one_address`, `reference_two_name`, `reference_two_phone`, `reference_two_address`, `created_by`, `created_at`, `updated_at`) VALUES
(16, 45, 1, 1, 2, '2023002', 'Robiul', 'e@demo.com', '2023-01-04', 'Male', 'A+', 'Married', 'Islam', '6556654645456654', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Permanent', '2023-12-04', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-12-04 05:08:06', '2023-12-04 05:08:06'),
(16, 45, 1, 1, 2, '2023002', 'Robiul', 'e@demo.com', '2023-01-04', 'Male', 'A+', 'Married', 'Islam', '6556654645456654', NULL, NULL, NULL, NULL, 'Father', NULL, NULL, NULL, 'Permanent', '2023-12-04', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-12-04 05:08:06', '2023-12-04 06:00:08'),
(1, 29, 1, 1, 1, '2', 'Md. Masum', 'masum@gmail.com', NULL, 'Male', 'B+', 'Married', NULL, NULL, '01500000000', '01700000000', NULL, NULL, NULL, NULL, 'Dhaka', 'Dhaka', 'Permanent', '2023-06-01', 2, 1, 'public/images/products/11-10-2023-04-33-10_MGL.jpg', 'public/images/products/11-10-2023-04-33-10-shahjalalasset_logo.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-19 01:43:09', '2023-12-04 01:01:19'),
(3, 31, 2, 1, 1, '1001', 'Mr. Reza', 'reza@gmail.com', NULL, 'Male', 'B+', NULL, NULL, NULL, '01900000000', NULL, NULL, NULL, NULL, NULL, 'Dhaka', 'Dhaka', 'Permanent', '2023-01-01', 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-19 22:52:40', '2023-12-04 01:20:01'),
(1, 29, 2, 1, 1, '2', 'Md. Masum', 'masum@gmail.com', NULL, 'Male', 'B+', 'Married', NULL, NULL, '01500000000', '01700000000', NULL, NULL, NULL, NULL, 'Dhaka', 'Dhaka', 'Permanent', '2023-06-01', 2, 1, 'public/images/products/11-10-2023-04-33-10_MGL.jpg', 'public/images/products/11-10-2023-04-33-10-shahjalalasset_logo.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-19 01:43:09', '2023-12-10 02:20:11'),
(1, 29, 2, 4, 1, '2', 'Md. Masum', 'masum@gmail.com', NULL, 'Male', 'B+', 'Married', NULL, NULL, '01500000000', '01700000000', NULL, NULL, NULL, NULL, 'Dhaka', 'Dhaka', 'Permanent', '2023-06-01', 2, 1, 'public/images/products/11-10-2023-04-33-10_MGL.jpg', 'public/images/products/11-10-2023-04-33-10-shahjalalasset_logo.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-19 01:43:09', '2023-12-24 00:39:32'),
(1, 29, 2, 4, 1, '2', 'Md. Masum', 'masumbdonly@gmail.com', NULL, 'Male', 'B+', 'Married', NULL, NULL, '01500000000', '01700000000', NULL, NULL, NULL, NULL, 'Dhaka', 'Dhaka', 'Permanent', '2023-06-01', 2, 1, 'public/images/products/11-10-2023-04-33-10_MGL.jpg', 'public/images/products/11-10-2023-04-33-10-shahjalalasset_logo.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-19 01:43:09', '2023-12-24 01:35:31'),
(3, 31, 1, 1, 1, '1001', 'Mr. Reza', 'reza@gmail.com', NULL, 'Male', 'B+', NULL, NULL, NULL, '01900000000', NULL, NULL, NULL, NULL, NULL, 'Dhaka', 'Dhaka', 'Permanent', '2023-01-01', 3, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-19 22:52:40', '2023-12-18 04:24:03'),
(2, 30, 2, 5, 2, '3', 'Mr. Munir', 'munir@gmail.com', NULL, 'Male', 'AB+', NULL, NULL, NULL, '01600000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Permanent', '2023-01-01', 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-19 22:45:47', '2023-12-04 01:18:10'),
(1, 29, 2, 4, 1, '2', 'Md. Masum', 'masum.azon@gmail.com', NULL, 'Male', 'B+', 'Married', NULL, NULL, '01500000000', '01700000000', NULL, NULL, NULL, NULL, 'Dhaka', 'Dhaka', 'Permanent', '2023-06-01', 2, 1, 'public/images/products/11-10-2023-04-33-10_MGL.jpg', 'public/images/products/11-10-2023-04-33-10-shahjalalasset_logo.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-09-19 01:43:09', '2023-12-24 01:51:55');

-- --------------------------------------------------------

--
-- Table structure for table `employee_delayin_earlyouts`
--

CREATE TABLE `employee_delayin_earlyouts` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `delay_in_time` time DEFAULT NULL,
  `early_out_time` time DEFAULT NULL,
  `done_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_delayin_earlyouts`
--

INSERT INTO `employee_delayin_earlyouts` (`id`, `employee_id`, `date`, `status`, `remarks`, `delay_in_time`, `early_out_time`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 40, '2023-10-01', 'out_of_office', 'Remarks', NULL, NULL, 'NRB LTD.', '2023-10-31 04:15:26', '2023-10-31 04:15:26'),
(2, 41, '2023-10-05', 'out_of_office', 'Remarks', NULL, NULL, 'NRB LTD.', '2023-10-31 04:33:07', '2023-10-31 04:33:07'),
(15, 40, '2024-03-05', 'absences', 'Remarks', NULL, NULL, 'Demo', '2024-03-05 00:23:28', '2024-03-05 00:23:28'),
(16, 38, '2024-03-06', 'absences', 'Remarks', NULL, NULL, 'Demo', '2024-03-06 03:31:41', '2024-03-06 03:31:41'),
(17, 39, '2024-03-06', 'out_of_office', 'Done', NULL, NULL, 'Demo', '2024-03-06 03:53:30', '2024-03-06 03:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `employee_education`
--

CREATE TABLE `employee_education` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `exam` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `institution` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `passingyear` date DEFAULT NULL,
  `result` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_education`
--

INSERT INTO `employee_education` (`id`, `employee_id`, `exam`, `institution`, `passingyear`, `result`, `created_at`, `updated_at`) VALUES
(1, 1, 'BSC', 'BUET', '2020-07-01', '3', '2023-09-19 01:44:12', '2023-10-10 04:54:01'),
(2, 4, 'MSC', 'BUET', '2023-10-01', '4', '2023-10-10 03:24:15', '2023-10-10 04:54:36'),
(3, 4, 'BSC', 'BUET', '2023-10-01', '3.5', '2023-10-10 03:26:49', '2023-10-10 05:19:55'),
(4, 1, 'MSC', 'BUET', '2023-10-01', '3.5', '2023-10-10 04:53:38', '2023-10-10 04:53:46'),
(5, 14, 'tryrty', 'rtyrt', '2023-11-26', '545', '2023-11-26 04:27:59', '2023-11-26 04:27:59'),
(6, 3, 'BSC', 'BUET', '2018-12-31', '4', '2023-12-31 01:50:08', '2023-12-31 04:53:55'),
(7, 3, 'MSC', 'DU', '2023-12-01', '4', '2023-12-31 04:45:38', '2023-12-31 04:49:17'),
(8, 38, 'BSC', 'BUET', '2024-03-01', '4', '2024-03-19 00:25:32', '2024-03-19 00:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `employee_job_histories`
--

CREATE TABLE `employee_job_histories` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `company_name` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `designation` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_job_histories`
--

INSERT INTO `employee_job_histories` (`id`, `employee_id`, `company_name`, `designation`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'NRB Ltd.', 'Programmer', '2022-09-01', NULL, '2023-09-19 01:45:19', '2023-10-10 04:52:51'),
(2, 3, 'NRB', 'Programmer', '2020-12-01', NULL, '2023-12-31 01:50:30', '2023-12-31 04:53:37'),
(3, 3, 'Company Name', 'Programmer', '2023-12-01', '2024-01-01', '2023-12-31 03:36:51', '2023-12-31 04:44:29'),
(4, 3, 'Arnab IT', 'Programmer', '2023-12-01', '2023-12-09', '2023-12-31 04:44:56', '2023-12-31 04:50:04');

-- --------------------------------------------------------

--
-- Table structure for table `employee_job_responsibilities`
--

CREATE TABLE `employee_job_responsibilities` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `job_responsibility` varchar(500) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employee_job_responsibilities`
--

INSERT INTO `employee_job_responsibilities` (`id`, `employee_id`, `job_responsibility`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 3, '2024 demo ....', '2023-12-01', NULL, '2023-12-31 03:45:47', '2023-12-31 04:55:18'),
(2, 3, 'Job Responsibility .....', NULL, NULL, '2023-12-31 04:15:35', '2023-12-31 04:55:18'),
(3, 1, '2024 ....', NULL, NULL, '2023-12-31 05:04:00', '2023-12-31 05:04:00'),
(4, 38, 'Responsibility', NULL, NULL, '2024-03-19 00:26:00', '2024-03-19 00:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_entries`
--

CREATE TABLE `employee_leave_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_year` smallint(6) DEFAULT NULL,
  `leave_settings_id` int(11) UNSIGNED DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `alternative_employee_id` int(11) DEFAULT NULL,
  `leave_application_date` varchar(10) DEFAULT NULL,
  `leave_start_date` varchar(10) DEFAULT NULL,
  `leave_end_date` varchar(10) DEFAULT NULL,
  `reporting_date` varchar(10) DEFAULT NULL,
  `late_of_leave_month` varchar(10) DEFAULT NULL,
  `leave_type` varchar(100) DEFAULT NULL,
  `total_days` int(4) DEFAULT 0,
  `no_of_late` int(4) DEFAULT 0,
  `department_status` tinyint(4) DEFAULT 0,
  `hr_status` tinyint(4) DEFAULT 0,
  `management_status` tinyint(4) DEFAULT 0,
  `final_status` tinyint(4) DEFAULT 0,
  `reason_for_leave` varchar(155) DEFAULT NULL,
  `remarks` varchar(155) DEFAULT NULL,
  `done_by` varchar(55) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_leave_entries`
--

INSERT INTO `employee_leave_entries` (`id`, `leave_year`, `leave_settings_id`, `branch_id`, `section_id`, `employee_id`, `alternative_employee_id`, `leave_application_date`, `leave_start_date`, `leave_end_date`, `reporting_date`, `late_of_leave_month`, `leave_type`, `total_days`, `no_of_late`, `department_status`, `hr_status`, `management_status`, `final_status`, `reason_for_leave`, `remarks`, `done_by`, `created_at`, `updated_at`) VALUES
(4, 2024, 3, NULL, 3, 41, NULL, '2024-03-20', '2024-03-20', '2024-03-20', NULL, NULL, 'Casual', 1, 0, 1, 0, 0, 1, NULL, NULL, 'Demo', '2024-03-20 03:14:40', '2024-03-21 00:27:20'),
(5, 2024, 2, NULL, 3, 40, NULL, '2024-03-20', '2024-03-20', '2024-03-20', NULL, NULL, 'Casual', 1, 0, 0, 0, 0, 0, NULL, NULL, 'Demo', '2024-03-20 03:28:02', '2024-03-20 03:28:02'),
(6, 2024, 2, NULL, 3, 40, NULL, '2024-03-20', '2024-03-20', '2024-03-20', NULL, NULL, 'Sick', 1, 0, 1, 0, 0, 1, NULL, 'Done', 'Demo', '2024-03-20 03:28:33', '2024-03-20 23:04:02');

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_settings`
--

CREATE TABLE `employee_leave_settings` (
  `id` int(11) NOT NULL,
  `leave_year` smallint(6) DEFAULT NULL,
  `branch_id` tinyint(4) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `casual_leave` decimal(5,2) DEFAULT 0.00,
  `sick_leave` decimal(5,2) DEFAULT 0.00,
  `annual_leave` decimal(5,2) DEFAULT 0.00,
  `special_leave` decimal(5,2) DEFAULT 0.00,
  `total_leave` decimal(5,2) DEFAULT 0.00,
  `status` tinyint(4) DEFAULT 0,
  `remarks` varchar(255) DEFAULT NULL,
  `done_by` varchar(155) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_leave_settings`
--

INSERT INTO `employee_leave_settings` (`id`, `leave_year`, `branch_id`, `section_id`, `employee_id`, `casual_leave`, `sick_leave`, `annual_leave`, `special_leave`, `total_leave`, `status`, `remarks`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 2024, NULL, 3, 39, '10.00', '14.00', '0.00', '0.00', '24.00', 1, NULL, 'Demo', '2024-03-19 23:29:19', '2024-05-30 06:14:25'),
(2, 2024, NULL, 3, 40, '10.00', '14.00', '0.00', '0.00', '24.00', 1, NULL, 'Demo', '2024-03-19 23:29:19', '2024-03-19 23:29:19'),
(3, 2024, NULL, 3, 41, '10.00', '14.00', '0.00', '0.00', '24.00', 1, NULL, 'Demo', '2024-03-19 23:29:19', '2024-03-19 23:29:19'),
(5, 2024, NULL, 3, 43, '10.00', '14.00', '0.00', '0.00', '24.00', 1, '', ' ', '2024-03-24 01:50:56', '2024-03-24 01:50:56'),
(6, 2024, NULL, 4, 38, '10.00', '14.00', '0.00', '0.00', '24.00', 1, NULL, 'Demo', '2024-03-24 01:57:10', '2024-03-24 01:57:10'),
(7, 2024, NULL, 4, 44, '10.00', '14.00', '0.00', '0.00', '24.00', 1, NULL, 'Demo', '2024-03-24 01:57:10', '2024-03-24 01:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `employee_ledgers`
--

CREATE TABLE `employee_ledgers` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `voucher_no` varchar(50) DEFAULT NULL,
  `ledger_title_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `debit` decimal(18,2) DEFAULT NULL,
  `credit` decimal(18,2) DEFAULT NULL,
  `remarks` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employee_ledgers`
--

INSERT INTO `employee_ledgers` (`id`, `employee_id`, `voucher_no`, `ledger_title_id`, `date`, `debit`, `credit`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 'VOU000001', 1, '2023-11-06', '0.00', '2000.00', 'Advance', '2023-11-06 04:04:11', '2023-11-06 04:04:11'),
(2, 1, 'VOU000002', 0, '2023-11-06', '1000.00', '0.00', 'Advance Return', '2023-11-06 04:04:26', '2023-11-06 04:04:26'),
(3, 1, 'VOU000003', 0, '2023-11-06', '200.00', '0.00', 'Advance Return', '2023-11-06 04:04:40', '2023-11-06 04:04:40'),
(4, 1, 'VOU000004', 1, '2023-11-06', '0.00', '700.00', 'Advance Return', '2023-11-06 04:04:58', '2023-11-06 04:04:58'),
(5, 2, 'VOU000005', 1, '2023-11-06', '0.00', '1000.00', 'Advance', '2023-11-06 04:05:17', '2023-11-06 04:05:17'),
(6, 2, 'VOU000006', 0, '2023-11-06', '200.00', '0.00', 'Advance Return', '2023-11-06 04:05:29', '2023-11-06 04:05:29'),
(7, 2, 'VOU000007', 1, '2023-11-06', '0.00', '500.00', 'Advance', '2023-11-06 04:05:40', '2023-11-06 04:05:40'),
(8, 3, 'VOU000008', 1, '2023-11-06', '0.00', '2000.00', 'Advance', '2023-11-06 04:05:59', '2023-11-06 04:05:59'),
(9, 3, 'VOU000009', 0, '2023-11-06', '500.00', '0.00', 'Advance Return', '2023-11-06 04:06:11', '2023-11-06 04:06:11');

-- --------------------------------------------------------

--
-- Table structure for table `employee_performances`
--

CREATE TABLE `employee_performances` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `performance_date` date DEFAULT NULL,
  `performance_id` int(10) UNSIGNED NOT NULL,
  `performance_rate` decimal(4,2) UNSIGNED DEFAULT 0.00,
  `remarks` varchar(255) DEFAULT NULL,
  `done_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salaries`
--

CREATE TABLE `employee_salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `payslip_type` tinyint(4) DEFAULT 1,
  `start_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `employee_salaries`
--

INSERT INTO `employee_salaries` (`id`, `employee_id`, `amount`, `payslip_type`, `start_date`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 20000.00, 1, '2023-09-01', 1, '2023-12-21 04:23:17', '2023-12-21 04:23:17'),
(2, 3, 30000.00, 1, '2023-08-01', 1, '2023-12-21 04:25:17', '2023-12-21 04:25:17'),
(3, 5, 35000.00, 1, '2023-08-01', 1, '2023-12-21 04:25:37', '2023-12-21 04:25:37'),
(4, 6, 40000.00, 1, '2023-08-01', 1, '2023-12-21 04:25:57', '2023-12-21 04:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `emp_branches`
--

CREATE TABLE `emp_branches` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `branch_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `branch_address` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `branch_mobile` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `branch_email` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `done_by` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `emp_branches`
--

INSERT INTO `emp_branches` (`id`, `branch_name`, `branch_code`, `branch_address`, `branch_mobile`, `branch_email`, `done_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Branch 1', 'BC-001', 'Dhaka, Bangladesh', '01300000000', 'branch@branch.com', NULL, 1, '2023-02-12 01:24:19', '2024-02-25 01:37:21'),
(2, 'Branch 2', 'BC-002', 'Dhaka, Bangladesh', '01500000000', 'branch@branch.com', NULL, 0, '2023-02-12 02:47:45', '2024-02-25 01:37:29'),
(3, 'Branch 3', 'BC-003', 'Dhaka, Bangladesh', '01800000000', 'branch@branch.com', NULL, 0, '2023-02-12 03:06:35', '2024-02-25 01:37:37'),
(4, 'Factory', 'BC-004', 'Dhaka, Bangladesh', '01300000000', 'factory@gsl.com', NULL, 1, '2023-11-22 23:47:45', '2024-02-25 01:37:55');

-- --------------------------------------------------------

--
-- Table structure for table `emp_departments`
--

CREATE TABLE `emp_departments` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `department_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `department_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `emp_departments`
--

INSERT INTO `emp_departments` (`id`, `branch_id`, `department_name`, `department_name_bangla`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'IT', NULL, 1, '2023-02-12 04:13:35', '2024-02-25 01:34:42'),
(2, 4, 'HR', NULL, 1, '2023-02-12 04:19:11', '2024-02-25 01:34:30'),
(3, 4, 'AC', NULL, 1, '2023-02-12 05:36:46', '2024-05-30 06:11:57'),
(6, 4, 'Department 1', NULL, 1, '2023-11-22 23:48:39', '2024-05-30 06:11:51'),
(7, NULL, 'Department', 'বিভাগ', 1, '2024-04-09 00:21:49', '2024-04-09 00:39:39');

-- --------------------------------------------------------

--
-- Table structure for table `emp_designations`
--

CREATE TABLE `emp_designations` (
  `id` int(11) NOT NULL,
  `designation_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `designation_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `emp_designations`
--

INSERT INTO `emp_designations` (`id`, `designation_name`, `designation_name_bangla`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Programmer', NULL, 1, '2023-02-12 06:19:10', '2023-02-12 06:26:09'),
(2, 'Marketing', NULL, 1, '2023-09-19 02:23:24', '2024-05-30 06:11:40'),
(3, 'AC', NULL, 1, '2023-10-12 00:22:43', '2023-10-12 00:22:43'),
(4, 'HR', NULL, 1, '2023-11-22 23:48:52', '2023-11-22 23:48:52'),
(5, 'Manager', 'Name Bangla', 1, '2023-11-23 05:53:43', '2024-04-09 00:55:48');

-- --------------------------------------------------------

--
-- Table structure for table `emp_grades`
--

CREATE TABLE `emp_grades` (
  `id` int(11) NOT NULL,
  `grade_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `grade_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_grades`
--

INSERT INTO `emp_grades` (`id`, `grade_name`, `grade_name_bangla`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Grade English', 'বাংলা', 1, '2024-01-31 03:40:10', '2024-05-30 06:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `emp_lines`
--

CREATE TABLE `emp_lines` (
  `id` int(11) NOT NULL,
  `line_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `line_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_lines`
--

INSERT INTO `emp_lines` (`id`, `line_name`, `line_name_bangla`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Line English', 'বাংলা', 1, '2024-01-31 02:05:07', '2024-05-30 06:13:28'),
(3, 'Line 1', NULL, 1, '2024-02-25 01:07:36', '2024-02-25 01:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `emp_postings`
--

CREATE TABLE `emp_postings` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `line_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `gross_salary` decimal(10,0) DEFAULT 0,
  `ac_number` int(20) DEFAULT NULL,
  `salary_section_id` int(11) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_postings`
--

INSERT INTO `emp_postings` (`id`, `employee_id`, `branch_id`, `type_id`, `department_id`, `section_id`, `line_id`, `designation_id`, `grade_id`, `gross_salary`, `ac_number`, `salary_section_id`, `joining_date`, `created_at`, `updated_at`) VALUES
(1, 38, 1, 5, 2, 4, 3, 5, 2, '50000', 100000001, 2, '2024-02-01', '2024-02-14 01:39:40', '2024-05-30 06:13:39'),
(2, 39, 1, 5, 1, 3, 2, 1, 2, '55555', 1546849687, 2, '2024-02-01', '2024-02-14 01:49:34', '2024-03-14 00:58:02'),
(3, 40, 1, 5, 1, 3, 2, 1, 2, '50000', 1445785875, 2, '2024-02-01', '2024-02-14 03:19:34', '2024-03-25 00:06:59'),
(4, 41, 1, 5, 2, 3, 2, 1, 2, '40000', 452345635, 2, '2024-02-01', '2024-02-14 03:23:48', '2024-03-14 00:58:34'),
(5, 43, 1, 7, 2, 3, 3, 2, 2, '30000', 216564518, 4, '2024-03-01', '2024-03-23 23:53:43', '2024-03-23 23:53:43'),
(6, 44, 1, 7, 1, 4, 3, 3, 2, '40000', 102521464, 4, '2024-03-01', '2024-03-24 00:08:44', '2024-04-17 02:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `emp_quite_types`
--

CREATE TABLE `emp_quite_types` (
  `id` int(11) NOT NULL,
  `quite_type_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `quite_type_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_quite_types`
--

INSERT INTO `emp_quite_types` (`id`, `quite_type_name`, `quite_type_name_bangla`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Quite Type English', 'বাংলা', 1, '2024-02-01 03:38:50', '2024-02-01 04:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `emp_salary_sections`
--

CREATE TABLE `emp_salary_sections` (
  `id` int(11) NOT NULL,
  `salary_section_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `salary_section_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_salary_sections`
--

INSERT INTO `emp_salary_sections` (`id`, `salary_section_name`, `salary_section_name_bangla`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Salary Section English', 'বাংলা', 1, '2024-01-31 05:04:23', '2024-02-01 04:10:58'),
(4, 'Salary Section Staff', NULL, 1, '2024-02-25 01:07:52', '2024-02-25 01:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `emp_sections`
--

CREATE TABLE `emp_sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `section_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_sections`
--

INSERT INTO `emp_sections` (`id`, `section_name`, `section_name_bangla`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Section 1', 'বাংলা', 1, '2024-01-31 01:52:57', '2024-03-19 01:58:16'),
(4, 'Section 2', NULL, 1, '2024-02-25 01:07:28', '2024-05-30 06:09:17');

-- --------------------------------------------------------

--
-- Table structure for table `emp_types`
--

CREATE TABLE `emp_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `type_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_types`
--

INSERT INTO `emp_types` (`id`, `type_name`, `type_name_bangla`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Type English', 'টাইপ বাংলা', 1, '2024-01-31 00:39:50', '2024-02-01 03:53:01'),
(7, 'Staff', NULL, 1, '2024-02-25 01:06:40', '2024-05-30 06:11:44'),
(8, 'Worker', NULL, 1, '2024-02-25 01:07:18', '2024-05-30 05:23:48');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finance_accounts`
--

CREATE TABLE `finance_accounts` (
  `id` int(11) UNSIGNED NOT NULL,
  `financegroup_id` int(11) DEFAULT NULL,
  `account_company_code` varchar(200) DEFAULT NULL,
  `account_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `account_mobile` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `account_email` varchar(100) DEFAULT NULL,
  `account_address` varchar(200) DEFAULT NULL,
  `account_group_code` varchar(150) DEFAULT NULL,
  `account_status` int(11) DEFAULT 0,
  `account_done_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `account_updated_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finance_accounts`
--

INSERT INTO `finance_accounts` (`id`, `financegroup_id`, `account_company_code`, `account_name`, `account_mobile`, `account_email`, `account_address`, `account_group_code`, `account_status`, `account_done_by`, `account_updated_by`, `created_at`, `updated_at`) VALUES
(7, 2, '01', 'Speed Money', NULL, NULL, NULL, '2', 1, 'Demo', NULL, '2024-05-14 05:49:36', '2024-05-14 05:49:36'),
(8, 6, '01', 'Current Asset Cash', NULL, NULL, NULL, NULL, 1, 'Demo', NULL, '2024-05-14 05:51:46', '2024-09-05 07:46:41'),
(9, 3, '01', 'Rent from Gulshan Land', NULL, NULL, NULL, '3', 1, 'Demo', NULL, '2024-05-14 05:58:26', '2024-05-14 05:58:26'),
(11, 13, '01', 'Cash', NULL, NULL, NULL, '100020002', 1, 'Demo', NULL, '2024-05-26 07:32:03', '2024-09-05 07:46:54'),
(12, 14, '01', 'bKash : 01580026521', NULL, NULL, NULL, '100020003', 1, 'Demo', NULL, '2024-05-26 07:45:31', '2024-09-05 07:48:05'),
(13, 2, '01', 'Purchase Account', NULL, NULL, NULL, '2', 1, 'Demo', NULL, '2024-05-26 09:46:56', '2024-05-26 09:46:56'),
(14, 3, '01', 'Sales Account', NULL, NULL, NULL, '3', 1, 'Demo', NULL, '2024-05-29 11:10:39', '2024-05-29 11:10:39'),
(22, 15, '01', 'Shahjalal Islami Bank A/C: 0145256', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-06-12 13:24:48', '2024-09-05 07:47:31'),
(106, 15, '01', 'Dutch Bangla Bank A/C: 123456', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-09-08 04:41:10', '2024-09-08 04:41:10'),
(159, 15, '01', 'DBBL.A/C.No.2978', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-09-30 15:41:30', '2024-09-30 15:41:30'),
(160, 15, '01', 'Al AIBL.A/c.No.2468', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-01 17:46:39', '2024-10-01 17:46:39'),
(161, 15, '01', 'PBL A/C-No.31083', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-01 17:56:39', '2024-10-01 17:56:39'),
(162, 15, '01', 'Dhaka Bank OD A/C-002143', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-01 18:05:47', '2024-10-01 18:05:47'),
(164, 15, '01', 'IBBL.A/c.No.4766', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-01 18:12:16', '2024-10-01 18:12:16'),
(166, 15, '01', 'PBL A/C-N0.1860', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-01 18:51:56', '2024-10-01 18:51:56'),
(169, 15, '01', 'SJIBL A/C No 8919', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-01 19:12:30', '2024-10-01 20:07:40'),
(170, 15, '01', 'NBL A/C NO. 4464/4761', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-01 21:29:56', '2024-10-01 21:29:56'),
(199, 15, '01', 'MMBL-C/C-Loan-00001', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-02 16:50:07', '2024-10-02 16:50:07'),
(205, 15, '01', 'MMBL-A/C-No.0004', NULL, NULL, NULL, '100020004', 1, 'Demo', NULL, '2024-10-02 17:23:03', '2024-10-02 17:23:03'),
(207, 11, '01', 'Perfume World', '01600000000', NULL, 'Dhaka, Bangladesh', '400010001', 1, 'Demo', NULL, '2024-10-16 11:27:46', '2024-10-16 11:27:46'),
(208, 7, '01', 'Mr. Shahed', '01681952640', NULL, 'Gulshan, Dhaka, Bangladesh', '100020001', 1, 'Demo', NULL, '2024-10-16 11:50:54', '2024-10-16 11:50:54'),
(209, 7, '01', 'Mr. Masum', '01681952640', NULL, 'Dhaka, Bangladesh', '100020001', 1, 'Demo', NULL, '2024-10-22 10:01:22', '2024-10-22 10:01:22'),
(210, 7, '01', 'Md. Masum', '01681952640', NULL, 'Dhaka, Bangladesh', '100020001', 1, 'Demo', 'Demo', '2024-10-30 07:03:14', '2024-10-30 07:12:52'),
(211, 11, '01', 'Mehera Service Station', '0175412125', NULL, 'Kuril', '400010001', 1, 'Demo', NULL, '2024-10-31 09:28:44', '2024-10-31 09:28:44'),
(212, 11, '01', 'Asian Pharmacy', '0175412125', NULL, 'Kuril', '400010001', 1, 'Demo', NULL, '2024-10-31 09:29:59', '2024-10-31 09:29:59'),
(213, 11, '01', 'Nabil Medicine', '0175412125', NULL, 'Kuril', '400010001', 1, 'Demo', NULL, '2024-10-31 09:30:31', '2024-10-31 09:30:31'),
(214, 11, '01', 'Goodluck CNG Filling Station', '0175412125', NULL, 'Kuril', '400010001', 1, 'Demo', NULL, '2024-10-31 09:31:21', '2024-10-31 09:31:21'),
(215, 11, '01', 'Anwar CNG', '0175412125', NULL, 'Kuril', '400010001', 1, 'Demo', NULL, '2024-10-31 09:32:00', '2024-10-31 09:32:00'),
(216, 11, '01', 'Gatsby Wear', '0175412125', NULL, 'Kuril', '400010001', 1, 'Demo', NULL, '2024-10-31 09:33:20', '2024-10-31 09:33:20'),
(217, 7, '01', 'Modern Health Care', '0175412125', NULL, 'Kuril', '100020001', 1, 'Demo', NULL, '2024-10-31 09:34:07', '2024-10-31 09:34:07'),
(218, 7, '01', 'Kazi Jahidul Haque', '0175412125', NULL, 'Kuril', '100020001', 1, 'Demo', NULL, '2024-10-31 09:34:33', '2024-10-31 09:34:33'),
(219, 7, '01', 'Care First Meds', '0175412125', NULL, 'Khawaja Tower ,11th floor, 95 Mohakhali, C/A বীর উত্তম এ কে খন্দকার সড়ক, ঢাকা 1212', '100020001', 1, 'Demo', NULL, '2024-10-31 09:35:35', '2024-10-31 09:35:35'),
(220, 7, '01', 'Rivu Filling Station', '0175412125', NULL, 'Kuril', '100020001', 1, 'Demo', NULL, '2024-10-31 09:36:10', '2024-10-31 09:36:10'),
(221, 7, '01', 'Salim & Sons Filling Station', '0175412125', NULL, 'Kuril', '100020001', 1, 'Demo', NULL, '2024-10-31 09:36:51', '2024-10-31 09:36:51'),
(222, 7, '01', 'R. K Filling Staion', '0175412125', NULL, 'Kuril', '100020001', 1, 'Demo', NULL, '2024-10-31 09:37:51', '2024-10-31 09:37:51'),
(223, 7, '01', 'Habib Pharmacy', '0175412125', NULL, 'Kuril', '100020001', 1, 'Demo', NULL, '2024-10-31 10:16:25', '2024-10-31 10:16:25'),
(224, 7, '01', 'Sonali Tissue PLC', '01681952638', 'sonali@gmail.com', 'Dhanmondi, Dhaka, Bangladesh', '100020001', 1, 'Demo', 'Demo', '2024-11-11 07:27:38', '2024-11-11 08:07:01'),
(225, 11, '01', 'Supplier Name 1002', '01681952640', 'supplier@gmail.com', 'Gulshan, Dhaka, Bangladesh', '400010001', 1, 'Demo', 'Demo', '2024-11-11 09:53:04', '2024-11-11 11:18:58'),
(226, 7, '01', 'Cash Customer', '016000001', NULL, 'Dhaka, Bangladesh', '100020001', 1, 'Demo', NULL, '2024-11-28 06:03:53', '2024-11-28 06:03:53');

-- --------------------------------------------------------

--
-- Table structure for table `finance_groups`
--

CREATE TABLE `finance_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_code` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `group_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `group_parents` varchar(150) DEFAULT '0',
  `group_company_code` varchar(200) DEFAULT NULL,
  `group_status` int(11) DEFAULT 0,
  `group_done_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `group_updated_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finance_groups`
--

INSERT INTO `finance_groups` (`id`, `group_code`, `group_name`, `group_parents`, `group_company_code`, `group_status`, `group_done_by`, `group_updated_by`, `created_at`, `updated_at`) VALUES
(1, '1', 'Assets', 'ROOT', '01', 1, 'Demo', NULL, '2024-05-08 03:07:03', '2024-05-08 03:07:03'),
(2, '2', 'Expenses', 'ROOT', '01', 1, 'Demo', NULL, '2024-05-08 03:10:07', '2024-05-08 03:10:07'),
(3, '3', 'Income', 'ROOT', '01', 1, 'Demo', NULL, '2024-05-08 03:10:23', '2024-05-08 03:10:23'),
(4, '4', 'Liabilities', 'ROOT', '01', 1, 'Demo', NULL, '2024-05-08 03:10:37', '2024-05-08 03:10:37'),
(5, '10001', 'Fixed Asset', '1', '01', 1, 'Demo', NULL, '2024-05-08 03:12:28', '2024-05-08 03:12:28'),
(6, '10002', 'Current Asset', '1', '01', 1, 'Demo', NULL, '2024-05-08 03:12:55', '2024-05-08 03:12:55'),
(7, '100020001', 'Account Receivable', '10002', '01', 1, 'Demo', NULL, '2024-05-08 03:17:19', '2024-05-08 03:17:19'),
(9, '100010001', 'Land', '10001', '01', 1, 'Demo', NULL, '2024-05-08 06:23:16', '2024-05-08 06:23:16'),
(10, '40001', 'Current Liabilities', '4', '01', 1, 'Demo', NULL, '2024-05-09 06:24:05', '2024-05-09 06:24:05'),
(11, '400010001', 'Account Payable', '40001', '01', 1, 'Demo', NULL, '2024-05-12 05:44:58', '2024-05-12 05:44:58'),
(12, '100010002', 'Gulshan Land', '10001', '01', 1, 'Demo', NULL, '2024-05-14 05:40:33', '2024-05-14 05:40:33'),
(13, '100020002', 'Cash in hand', '10002', '01', 1, 'Demo', NULL, '2024-05-26 07:15:34', '2024-05-26 07:15:34'),
(14, '100020003', 'Mobile Bank', '10002', '01', 1, 'Demo', NULL, '2024-05-26 07:45:04', '2024-05-26 07:45:04'),
(15, '100020004', 'Bank Account', '10002', '01', 1, 'Demo', NULL, '2024-06-12 13:23:45', '2024-06-12 13:23:45');

-- --------------------------------------------------------

--
-- Table structure for table `finance_transactions`
--

CREATE TABLE `finance_transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `company_code` varchar(200) DEFAULT NULL,
  `delivery_challan_no` varchar(200) DEFAULT NULL,
  `invoice_no` varchar(200) DEFAULT NULL,
  `voucher_no` varchar(200) DEFAULT NULL,
  `voucher_date` date DEFAULT NULL,
  `acid` int(11) DEFAULT NULL,
  `to_acc_name` varchar(150) DEFAULT NULL,
  `type` varchar(120) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT 0.00,
  `balance_type` varchar(120) DEFAULT NULL,
  `payment_type` varchar(155) DEFAULT NULL,
  `narration` text DEFAULT NULL,
  `cheque_no` varchar(100) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `cheque_type` varchar(100) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `transaction_by` varchar(100) DEFAULT NULL,
  `invoice_type` int(11) DEFAULT NULL,
  `pending_adjustment` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `done_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finance_transactions`
--

INSERT INTO `finance_transactions` (`id`, `company_code`, `delivery_challan_no`, `invoice_no`, `voucher_no`, `voucher_date`, `acid`, `to_acc_name`, `type`, `amount`, `balance_type`, `payment_type`, `narration`, `cheque_no`, `cheque_date`, `cheque_type`, `transaction_date`, `transaction_by`, `invoice_type`, `pending_adjustment`, `status`, `done_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '01', NULL, 'PUR000001', '01PV000001', '2024-10-01', 13, 'Perfume World', 'PV', '41500.00', 'Dr', NULL, 'Invoice No:PUR000001, Name:Manola Men Perfume, Qty:25 X 1500, Total:37500\n\nName:Manola Perfume Oil, Qty:20 X 200, Total:4000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:24:30', '2024-10-27 10:24:30'),
(2, '01', NULL, 'PUR000001', '01PV000001', '2024-10-01', 207, 'Purchase Account', 'PV', '41500.00', 'Cr', NULL, 'Invoice No:PUR000001, Name:Manola Men Perfume, Qty:25 X 1500, Total:37500\n\nName:Manola Perfume Oil, Qty:20 X 200, Total:4000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:24:30', '2024-10-27 10:24:30'),
(3, '01', NULL, 'PUR000001', '01PV000001', '2024-10-01', 207, 'Cash', 'PV', '41500.00', 'Dr', 'Cash in hand', 'Invoice No:PUR000001, Cash Payment To: Perfume World, Through Cash, Payment Amount:41,500.00 TK Paid\n', '0', NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:24:30', '2024-10-27 10:24:30'),
(4, '01', NULL, 'PUR000001', '01PV000001', '2024-10-01', 11, 'Perfume World', 'PV', '41500.00', 'Cr', 'Cash in hand', 'Invoice No:PUR000001, Cash Payment To: Perfume World, Through Cash, Payment Amount:41,500.00 TK Paid\n', '0', NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:24:30', '2024-10-27 10:24:30'),
(5, '01', NULL, 'PUR000002', '01PV000002', '2024-10-05', 13, 'Perfume World', 'PV', '53500.00', 'Dr', NULL, 'Invoice No:PUR000002, Name:Manola Men Perfume, Qty:30 X 1600, Total:48000\n\nName:Manola Perfume Oil, Qty:25 X 220, Total:5500\n', NULL, NULL, NULL, '2024-10-05', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:25:55', '2024-10-27 10:25:55'),
(6, '01', NULL, 'PUR000002', '01PV000002', '2024-10-05', 207, 'Purchase Account', 'PV', '53500.00', 'Cr', NULL, 'Invoice No:PUR000002, Name:Manola Men Perfume, Qty:30 X 1600, Total:48000\n\nName:Manola Perfume Oil, Qty:25 X 220, Total:5500\n', NULL, NULL, NULL, '2024-10-05', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:25:55', '2024-10-27 10:25:55'),
(7, '01', NULL, 'PUR000002', '01PV000002', '2024-10-05', 207, 'Cash', 'PV', '53500.00', 'Dr', 'Cash in hand', 'Invoice No:PUR000002, Cash Payment To: Perfume World, Through Cash, Payment Amount:53,500.00 TK Done\n', '0', NULL, NULL, '2024-10-05', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:25:55', '2024-10-27 10:25:55'),
(8, '01', NULL, 'PUR000002', '01PV000002', '2024-10-05', 11, 'Perfume World', 'PV', '53500.00', 'Cr', 'Cash in hand', 'Invoice No:PUR000002, Cash Payment To: Perfume World, Through Cash, Payment Amount:53,500.00 TK Done\n', '0', NULL, NULL, '2024-10-05', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:25:55', '2024-10-27 10:25:55'),
(9, '01', NULL, 'PUR000003', '01PV000003', '2024-10-10', 13, 'Perfume World', 'PV', '75500.00', 'Dr', NULL, 'Invoice No:PUR000003, Name:Manola Men Perfume, Qty:40 X 1700, Total:67500\n\nName:Manola Perfume Oil, Qty:35 X 240, Total:8000\n', NULL, NULL, NULL, '2024-10-10', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:28:40', '2024-10-27 10:28:40'),
(10, '01', NULL, 'PUR000003', '01PV000003', '2024-10-10', 207, 'Purchase Account', 'PV', '75500.00', 'Cr', NULL, 'Invoice No:PUR000003, Name:Manola Men Perfume, Qty:40 X 1700, Total:67500\n\nName:Manola Perfume Oil, Qty:35 X 240, Total:8000\n', NULL, NULL, NULL, '2024-10-10', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:28:40', '2024-10-27 10:28:40'),
(11, '01', NULL, 'PUR000003', '01PV000003', '2024-10-10', 207, 'Cash', 'PV', '75500.00', 'Dr', 'Cash in hand', 'Invoice No:PUR000003, Cash Payment To: Perfume World, Through Cash, Payment Amount:75,500.00 TK Paid\n', '0', NULL, NULL, '2024-10-10', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:28:40', '2024-10-27 10:28:40'),
(12, '01', NULL, 'PUR000003', '01PV000003', '2024-10-10', 11, 'Perfume World', 'PV', '75500.00', 'Cr', 'Cash in hand', 'Invoice No:PUR000003, Cash Payment To: Perfume World, Through Cash, Payment Amount:75,500.00 TK Paid\n', '0', NULL, NULL, '2024-10-10', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:28:40', '2024-10-27 10:28:40'),
(17, '01', 'CHA000002', 'INV000002', '01SV000002', '2024-10-25', 209, 'Sales Account', 'SV', '36000.00', 'Dr', NULL, 'Invoice:INV000002, Name:Manola Men Perfume, Qty:15 X 2000, Total:30000\n\nName:Manola Perfume Oil, Qty:20 X 300, Total:6000\n', NULL, NULL, NULL, '2024-10-25', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:30:31', '2024-10-27 10:30:31'),
(18, '01', 'CHA000002', 'INV000002', '01SV000002', '2024-10-25', 14, 'Mr. Masum', 'SV', '36000.00', 'Cr', NULL, 'Invoice:INV000002, Name:Manola Men Perfume, Qty:15 X 2000, Total:30000\n\nName:Manola Perfume Oil, Qty:20 X 300, Total:6000\n', NULL, NULL, NULL, '2024-10-25', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 10:30:31', '2024-10-27 10:30:31'),
(19, '01', 'CHA000003', 'INV000003', '01SV000003', '2024-10-27', 208, 'Sales Account', 'SV', '18602.00', 'Dr', NULL, 'Invoice:INV000003, Name:Manola Men Perfume, Qty:9 X 1850.5, Total:16601\n\nName:Manola Perfume Oil, Qty:7 X 288.5, Total:2001\n', NULL, NULL, NULL, '2024-10-27', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 11:41:14', '2024-10-27 11:41:14'),
(20, '01', 'CHA000003', 'INV000003', '01SV000003', '2024-10-27', 14, 'Mr. Shahed', 'SV', '18602.00', 'Cr', NULL, 'Invoice:INV000003, Name:Manola Men Perfume, Qty:9 X 1850.5, Total:16601\n\nName:Manola Perfume Oil, Qty:7 X 288.5, Total:2001\n', NULL, NULL, NULL, '2024-10-27', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-27 11:41:14', '2024-10-27 11:41:14'),
(21, '01', 'CHA000004', 'INV000004', '01SV000004', '2024-10-28', 208, 'Sales Account', 'SV', '11500.00', 'Dr', NULL, 'Invoice:INV000004, Name:Manola Men Perfume, Qty:5 X 2000, Total:10000\n\nName:Manola Perfume Oil, Qty:5 X 300, Total:1500\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 08:00:05', '2024-10-28 08:00:05'),
(22, '01', 'CHA000004', 'INV000004', '01SV000004', '2024-10-28', 14, 'Mr. Shahed', 'SV', '11500.00', 'Cr', NULL, 'Invoice:INV000004, Name:Manola Men Perfume, Qty:5 X 2000, Total:10000\n\nName:Manola Perfume Oil, Qty:5 X 300, Total:1500\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 08:00:05', '2024-10-28 08:00:05'),
(23, '01', 'CHA000005', 'INV000005', '01SV000005', '2024-10-01', 208, 'Sales Account', 'SV', '1000.00', 'Dr', NULL, 'Invoice:INV000005, Name:Manola Men Perfume, Qty:0.5 X 2000, Total:1000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:17:58', '2024-10-28 09:17:58'),
(24, '01', 'CHA000005', 'INV000005', '01SV000005', '2024-10-01', 14, 'Mr. Shahed', 'SV', '1000.00', 'Cr', NULL, 'Invoice:INV000005, Name:Manola Men Perfume, Qty:0.5 X 2000, Total:1000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:17:58', '2024-10-28 09:17:58'),
(25, '01', 'CHA000005', 'INV000005', '01SV000005', '2024-10-01', 208, 'Cash', 'SV', '1000.00', 'Cr', 'Cash in hand', 'Invoice:INV000005, Cash Received From: Mr. Shahed, Through Cash Received Amount:1,000.00 TK Remarks .....\n', '0', NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:17:58', '2024-10-28 09:17:58'),
(26, '01', 'CHA000005', 'INV000005', '01SV000005', '2024-10-01', 11, 'Mr. Shahed', 'SV', '1000.00', 'Dr', 'Cash in hand', 'Invoice:INV000005, Cash Received From: Mr. Shahed, Through Cash Received Amount:1,000.00 TK Remarks .....\n', '0', NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:17:58', '2024-10-28 09:17:58'),
(27, '01', 'CHA000006', 'INV000006', '01SV000006', '2024-10-28', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000006, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:37:18', '2024-10-28 09:37:18'),
(28, '01', 'CHA000006', 'INV000006', '01SV000006', '2024-10-28', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000006, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:37:18', '2024-10-28 09:37:18'),
(29, '01', 'CHA000007', 'INV000007', '01SV000007', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000007, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:39:21', '2024-10-28 09:39:21'),
(30, '01', 'CHA000007', 'INV000007', '01SV000007', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000007, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:39:21', '2024-10-28 09:39:21'),
(31, '01', 'CHA000008', 'INV000008', '01SV000008', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000008, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:40:31', '2024-10-28 09:40:31'),
(32, '01', 'CHA000008', 'INV000008', '01SV000008', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000008, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:40:31', '2024-10-28 09:40:31'),
(33, '01', 'CHA000009', 'INV000009', '01SV000009', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000009, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:41:11', '2024-10-28 09:41:11'),
(34, '01', 'CHA000009', 'INV000009', '01SV000009', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000009, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:41:11', '2024-10-28 09:41:11'),
(35, '01', 'CHA000010', 'INV000010', '01SV000010', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000010, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:43:02', '2024-10-28 09:43:02'),
(36, '01', 'CHA000010', 'INV000010', '01SV000010', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000010, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:43:02', '2024-10-28 09:43:02'),
(37, '01', 'CHA000011', 'INV000011', '01SV000011', '2024-10-28', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000011, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:49:26', '2024-10-28 09:49:26'),
(38, '01', 'CHA000011', 'INV000011', '01SV000011', '2024-10-28', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000011, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:49:26', '2024-10-28 09:49:26'),
(39, '01', 'CHA000012', 'INV000012', '01SV000012', '2024-10-28', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000012, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:53:34', '2024-10-28 09:53:34'),
(40, '01', 'CHA000012', 'INV000012', '01SV000012', '2024-10-28', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000012, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:53:34', '2024-10-28 09:53:34'),
(41, '01', 'CHA000013', 'INV000013', '01SV000013', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000013, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:59:23', '2024-10-28 09:59:23'),
(42, '01', 'CHA000013', 'INV000013', '01SV000013', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000013, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 09:59:23', '2024-10-28 09:59:23'),
(43, '01', 'CHA000014', 'INV000014', '01SV000014', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000014, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:02:09', '2024-10-28 10:02:09'),
(44, '01', 'CHA000014', 'INV000014', '01SV000014', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000014, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:02:09', '2024-10-28 10:02:09'),
(45, '01', 'CHA000015', 'INV000015', '01SV000015', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000015, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:04:09', '2024-10-28 10:04:09'),
(46, '01', 'CHA000015', 'INV000015', '01SV000015', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000015, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:04:09', '2024-10-28 10:04:09'),
(47, '01', 'CHA000016', 'INV000016', '01SV000016', '2024-10-28', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000016, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:07:14', '2024-10-28 10:07:14'),
(48, '01', 'CHA000016', 'INV000016', '01SV000016', '2024-10-28', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000016, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:07:14', '2024-10-28 10:07:14'),
(49, '01', 'CHA000017', 'INV000017', '01SV000017', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000017, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:08:41', '2024-10-28 10:08:41'),
(50, '01', 'CHA000017', 'INV000017', '01SV000017', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000017, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:08:41', '2024-10-28 10:08:41'),
(51, '01', 'CHA000018', 'INV000018', '01SV000018', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000018, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:09:36', '2024-10-28 10:09:36'),
(52, '01', 'CHA000018', 'INV000018', '01SV000018', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000018, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:09:36', '2024-10-28 10:09:36'),
(53, '01', 'CHA000019', 'INV000019', '01SV000019', '2024-10-28', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000019, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:13:26', '2024-10-28 10:13:26'),
(54, '01', 'CHA000019', 'INV000019', '01SV000019', '2024-10-28', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000019, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:13:26', '2024-10-28 10:13:26'),
(55, '01', 'CHA000020', 'INV000020', '01SV000020', '2024-10-01', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000020, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:20:48', '2024-10-28 10:20:48'),
(56, '01', 'CHA000020', 'INV000020', '01SV000020', '2024-10-01', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000020, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:20:48', '2024-10-28 10:20:48'),
(57, '01', 'CHA000021', 'INV000021', '01SV000021', '2024-10-01', 208, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000021, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:23:01', '2024-10-28 10:23:01'),
(58, '01', 'CHA000021', 'INV000021', '01SV000021', '2024-10-01', 14, 'Mr. Shahed', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000021, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:23:01', '2024-10-28 10:23:01'),
(59, '01', 'CHA000022', 'INV000022', '01SV000022', '2024-10-01', 208, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000022, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:25:08', '2024-10-28 10:25:08'),
(60, '01', 'CHA000022', 'INV000022', '01SV000022', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000022, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:25:08', '2024-10-28 10:25:08'),
(61, '01', 'CHA000023', 'INV000023', '01SV000023', '2024-10-28', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000023, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:32:20', '2024-10-28 10:32:20'),
(62, '01', 'CHA000023', 'INV000023', '01SV000023', '2024-10-28', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000023, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:32:20', '2024-10-28 10:32:20'),
(63, '01', 'CHA000024', 'INV000024', '01SV000024', '2024-10-01', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000024, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:35:22', '2024-10-28 10:35:22'),
(64, '01', 'CHA000024', 'INV000024', '01SV000024', '2024-10-01', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000024, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:35:22', '2024-10-28 10:35:22'),
(65, '01', 'CHA000025', 'INV000025', '01SV000025', '2024-10-28', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000025, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:38:18', '2024-10-28 10:38:18'),
(66, '01', 'CHA000025', 'INV000025', '01SV000025', '2024-10-28', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000025, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:38:18', '2024-10-28 10:38:18'),
(67, '01', 'CHA000026', 'INV000026', '01SV000026', '2024-10-28', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000026, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:40:43', '2024-10-28 10:40:43'),
(68, '01', 'CHA000026', 'INV000026', '01SV000026', '2024-10-28', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000026, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:40:43', '2024-10-28 10:40:43'),
(69, '01', 'CHA000027', 'INV000027', '01SV000027', '2024-10-28', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000027, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:41:34', '2024-10-28 10:41:34'),
(70, '01', 'CHA000027', 'INV000027', '01SV000027', '2024-10-28', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000027, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:41:34', '2024-10-28 10:41:34'),
(71, '01', 'CHA000028', 'INV000028', '01SV000028', '2024-10-01', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000028, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:44:46', '2024-10-28 10:44:46'),
(72, '01', 'CHA000028', 'INV000028', '01SV000028', '2024-10-01', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000028, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:44:46', '2024-10-28 10:44:46'),
(73, '01', 'CHA000029', 'INV000029', '01SV000029', '2024-10-01', 208, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000029, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:48:29', '2024-10-28 10:48:29'),
(74, '01', 'CHA000029', 'INV000029', '01SV000029', '2024-10-01', 14, 'Mr. Shahed', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000029, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:48:29', '2024-10-28 10:48:29'),
(75, '01', 'CHA000030', 'INV000030', '01SV000030', '2024-10-28', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000030, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:50:45', '2024-10-28 10:50:45'),
(76, '01', 'CHA000030', 'INV000030', '01SV000030', '2024-10-28', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000030, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:50:45', '2024-10-28 10:50:45'),
(77, '01', 'CHA000031', 'INV000031', '01SV000031', '2024-10-28', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000031, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:54:38', '2024-10-28 10:54:38'),
(78, '01', 'CHA000031', 'INV000031', '01SV000031', '2024-10-28', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000031, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:54:38', '2024-10-28 10:54:38'),
(79, '01', 'CHA000032', 'INV000032', '01SV000032', '2024-10-28', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000032, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:56:06', '2024-10-28 10:56:06'),
(80, '01', 'CHA000032', 'INV000032', '01SV000032', '2024-10-28', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000032, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:56:06', '2024-10-28 10:56:06'),
(81, '01', 'CHA000033', 'INV000033', '01SV000033', '2024-10-28', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000033, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:57:19', '2024-10-28 10:57:19'),
(82, '01', 'CHA000033', 'INV000033', '01SV000033', '2024-10-28', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000033, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:57:19', '2024-10-28 10:57:19'),
(83, '01', 'CHA000034', 'INV000034', '01SV000034', '2024-10-05', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000034, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-05', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:58:22', '2024-10-28 10:58:22'),
(84, '01', 'CHA000034', 'INV000034', '01SV000034', '2024-10-05', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000034, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-05', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 10:58:22', '2024-10-28 10:58:22'),
(85, '01', 'CHA000035', 'INV000035', '01SV000035', '2024-10-04', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000035, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-04', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:00:14', '2024-10-28 11:00:14'),
(86, '01', 'CHA000035', 'INV000035', '01SV000035', '2024-10-04', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000035, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-04', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:00:14', '2024-10-28 11:00:14'),
(87, '01', 'CHA000036', 'INV000036', '01SV000036', '2024-10-08', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000036, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-08', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:00:51', '2024-10-28 11:00:51'),
(88, '01', 'CHA000036', 'INV000036', '01SV000036', '2024-10-08', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000036, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-08', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:00:51', '2024-10-28 11:00:51'),
(89, '01', 'CHA000037', 'INV000037', '01SV000037', '2024-10-28', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000037, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:14:26', '2024-10-28 11:14:26'),
(90, '01', 'CHA000037', 'INV000037', '01SV000037', '2024-10-28', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000037, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:14:26', '2024-10-28 11:14:26'),
(91, '01', 'CHA000038', 'INV000038', '01SV000038', '2024-10-28', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000038, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:15:14', '2024-10-28 11:15:14'),
(92, '01', 'CHA000038', 'INV000038', '01SV000038', '2024-10-28', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000038, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-28', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:15:14', '2024-10-28 11:15:14'),
(93, '01', 'CHA000039', 'INV000039', '01SV000039', '2024-10-01', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000039, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:15:53', '2024-10-28 11:15:53'),
(94, '01', 'CHA000039', 'INV000039', '01SV000039', '2024-10-01', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000039, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:15:53', '2024-10-28 11:15:53'),
(95, '01', 'CHA000040', 'INV000040', '01SV000040', '2024-10-01', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000040, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:16:47', '2024-10-28 11:16:47'),
(96, '01', 'CHA000040', 'INV000040', '01SV000040', '2024-10-01', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000040, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:16:47', '2024-10-28 11:16:47'),
(97, '01', 'CHA000041', 'INV000041', '01SV000041', '2024-10-16', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000041, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-16', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:40:23', '2024-10-28 11:40:23'),
(98, '01', 'CHA000041', 'INV000041', '01SV000041', '2024-10-16', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000041, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-16', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:40:23', '2024-10-28 11:40:23'),
(99, '01', 'CHA000042', 'INV000042', '01SV000042', '2024-10-01', 209, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000042, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:45:50', '2024-10-28 11:45:50'),
(100, '01', 'CHA000042', 'INV000042', '01SV000042', '2024-10-01', 14, 'Mr. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000042, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:45:50', '2024-10-28 11:45:50'),
(101, '01', 'CHA000043', 'INV000043', '01SV000043', '2024-10-01', 209, 'Sales Account', 'SV', '300.00', 'Dr', NULL, 'Invoice:INV000043, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:48:49', '2024-10-28 11:48:49'),
(102, '01', 'CHA000043', 'INV000043', '01SV000043', '2024-10-01', 14, 'Mr. Masum', 'SV', '300.00', 'Cr', NULL, 'Invoice:INV000043, Name:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 11:48:49', '2024-10-28 11:48:49'),
(103, '01', 'CHA000044', 'INV000044', '01SV000044', '2024-10-01', 208, 'Sales Account', 'SV', '2600.00', 'Dr', NULL, 'Invoice:INV000044, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Manola Perfume Oil, Qty:2 X 300, Total:600\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 12:41:45', '2024-10-28 12:41:45'),
(104, '01', 'CHA000044', 'INV000044', '01SV000044', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2600.00', 'Cr', NULL, 'Invoice:INV000044, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Manola Perfume Oil, Qty:2 X 300, Total:600\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 12:41:45', '2024-10-28 12:41:45'),
(105, '01', 'CHA000045', 'INV000045', '01SV000045', '2024-10-01', 208, 'Sales Account', 'SV', '350.00', 'Dr', NULL, 'Invoice:INV000045, Name:Manola Perfume Oil, Qty:1 X 350, Total:350\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 12:56:47', '2024-10-28 12:56:47'),
(106, '01', 'CHA000045', 'INV000045', '01SV000045', '2024-10-01', 14, 'Mr. Shahed', 'SV', '350.00', 'Cr', NULL, 'Invoice:INV000045, Name:Manola Perfume Oil, Qty:1 X 350, Total:350\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 12:56:47', '2024-10-28 12:56:47'),
(107, '01', 'CHA000046', 'INV000046', '01SV000046', '2024-10-25', 208, 'Sales Account', 'SV', '2500.00', 'Dr', NULL, 'Invoice:INV000046, Name:Manola Men Perfume, Qty:1 X 1800, Total:1800\n\nName:Manola Perfume Oil, Qty:2 X 350, Total:700\n', NULL, NULL, NULL, '2024-10-25', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:00:40', '2024-10-28 13:00:40'),
(108, '01', 'CHA000046', 'INV000046', '01SV000046', '2024-10-25', 14, 'Mr. Shahed', 'SV', '2500.00', 'Cr', NULL, 'Invoice:INV000046, Name:Manola Men Perfume, Qty:1 X 1800, Total:1800\n\nName:Manola Perfume Oil, Qty:2 X 350, Total:700\n', NULL, NULL, NULL, '2024-10-25', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:00:40', '2024-10-28 13:00:40'),
(109, '01', 'CHA000047', 'INV000047', '01SV000047', '2024-10-23', 209, 'Sales Account', 'SV', '2300.00', 'Dr', NULL, 'Invoice:INV000047, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-23', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:02:33', '2024-10-28 13:02:33'),
(110, '01', 'CHA000047', 'INV000047', '01SV000047', '2024-10-23', 14, 'Mr. Masum', 'SV', '2300.00', 'Cr', NULL, 'Invoice:INV000047, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-23', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:02:33', '2024-10-28 13:02:33'),
(111, '01', NULL, 'PUR000004', '01PV000004', '2024-10-01', 13, 'Perfume World', 'PV', '1600.00', 'Dr', NULL, 'Invoice No:PUR000004, Name:Manola Men Perfume, Qty:1 X 1600, Total:1600\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:04:33', '2024-10-28 13:04:33'),
(112, '01', NULL, 'PUR000004', '01PV000004', '2024-10-01', 207, 'Purchase Account', 'PV', '1600.00', 'Cr', NULL, 'Invoice No:PUR000004, Name:Manola Men Perfume, Qty:1 X 1600, Total:1600\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:04:33', '2024-10-28 13:04:33'),
(113, '01', NULL, 'PUR000005', '01PV000005', '2024-10-01', 13, 'Perfume World', 'PV', '1800.00', 'Dr', NULL, 'Invoice No:PUR000005, Name:Manola Men Perfume, Qty:1 X 1600, Total:1600\n\nName:Manola Perfume Oil, Qty:1 X 200, Total:200\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:08:47', '2024-10-28 13:08:47'),
(114, '01', NULL, 'PUR000005', '01PV000005', '2024-10-01', 207, 'Purchase Account', 'PV', '1800.00', 'Cr', NULL, 'Invoice No:PUR000005, Name:Manola Men Perfume, Qty:1 X 1600, Total:1600\n\nName:Manola Perfume Oil, Qty:1 X 200, Total:200\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:08:47', '2024-10-28 13:08:47'),
(115, '01', NULL, 'PUR000006', '01PV000006', '2024-10-01', 13, 'Perfume World', 'PV', '1850.00', 'Dr', NULL, 'Invoice No:PUR000006, Name:Manola Men Perfume, Qty:1 X 1600, Total:1600\n\nName:Manola Perfume Oil, Qty:1 X 250, Total:250\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:09:53', '2024-10-28 13:09:53'),
(116, '01', NULL, 'PUR000006', '01PV000006', '2024-10-01', 207, 'Purchase Account', 'PV', '1850.00', 'Cr', NULL, 'Invoice No:PUR000006, Name:Manola Men Perfume, Qty:1 X 1600, Total:1600\n\nName:Manola Perfume Oil, Qty:1 X 250, Total:250\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:09:53', '2024-10-28 13:09:53'),
(117, '01', 'CHA000048', 'INV000048', '01SV000048', '2024-10-01', 208, 'Sales Account', 'SV', '2300.00', 'Dr', NULL, 'Invoice:INV000048, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:10:43', '2024-10-28 13:10:43'),
(118, '01', 'CHA000048', 'INV000048', '01SV000048', '2024-10-01', 14, 'Mr. Shahed', 'SV', '2300.00', 'Cr', NULL, 'Invoice:INV000048, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Manola Perfume Oil, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-10-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-10-28 13:10:43', '2024-10-28 13:10:43'),
(119, '01', NULL, 'RTP000001', '01PV000007', '2024-11-14', 13, 'Perfume World', 'PR', '8500.00', 'Cr', NULL, 'Invoice No:RTP000001, Name:Manola Men Perfume, Qty:5 X 1500.00, Total:7500\n\nName:Manola Perfume Oil, Qty:5 X 200.00, Total:1000\n', NULL, NULL, NULL, '2024-11-14', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-14 06:43:32', '2024-11-14 06:43:32'),
(120, '01', NULL, 'RTP000001', '01PV000007', '2024-11-14', 207, 'Purchase Account', 'PR', '8500.00', 'Dr', NULL, 'Invoice No:RTP000001, Name:Manola Men Perfume, Qty:5 X 1500.00, Total:7500\n\nName:Manola Perfume Oil, Qty:5 X 200.00, Total:1000\n', NULL, NULL, NULL, '2024-11-14', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-14 06:43:32', '2024-11-14 06:43:32'),
(121, '01', NULL, 'RTP000006', '01PV000008', '2024-11-14', 13, 'Perfume World', 'PR', '1850.00', 'Cr', NULL, 'Invoice No:RTP000006, Name:Manola Men Perfume, Qty:1 X 1600.00, Total:1600\n\nName:Manola Perfume Oil, Qty:1 X 250.00, Total:250\n', NULL, NULL, NULL, '2024-11-14', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-14 09:50:36', '2024-11-14 09:50:36'),
(122, '01', NULL, 'RTP000006', '01PV000008', '2024-11-14', 207, 'Purchase Account', 'PR', '1850.00', 'Dr', NULL, 'Invoice No:RTP000006, Name:Manola Men Perfume, Qty:1 X 1600.00, Total:1600\n\nName:Manola Perfume Oil, Qty:1 X 250.00, Total:250\n', NULL, NULL, NULL, '2024-11-14', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-14 09:50:36', '2024-11-14 09:50:36'),
(123, '01', NULL, 'RTP000003', '01PV000009', '2024-11-14', 13, 'Perfume World', 'PR', '19700.00', 'Cr', NULL, 'Invoice No:RTP000003, Name:Manola Men Perfume, Qty:10 X 1700.00, Total:16500\n\nName:Manola Perfume Oil, Qty:15 X 240.00, Total:3200\n', NULL, NULL, NULL, '2024-11-14', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-14 09:51:10', '2024-11-14 09:51:10'),
(124, '01', NULL, 'RTP000003', '01PV000009', '2024-11-14', 207, 'Purchase Account', 'PR', '19700.00', 'Dr', NULL, 'Invoice No:RTP000003, Name:Manola Men Perfume, Qty:10 X 1700.00, Total:16500\n\nName:Manola Perfume Oil, Qty:15 X 240.00, Total:3200\n', NULL, NULL, NULL, '2024-11-14', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-14 09:51:10', '2024-11-14 09:51:10'),
(125, '01', 'CHA000049', 'INV000049', '01SV000049', '2024-11-17', 209, 'Sales Account', 'SV', '4870.00', 'Dr', NULL, 'Invoice:INV000049, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Manola Perfume Oil, Qty:2 X 300, Total:600\n\nName:Manola Hair Oil, Qty:3 X 590, Total:1770\n\nName:Outside Product, Qty:1 X 200, Total:200\n\nName:Conveyance, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-11-17', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(126, '01', 'CHA000049', 'INV000049', '01SV000049', '2024-11-17', 14, 'Mr. Masum', 'SV', '4870.00', 'Cr', NULL, 'Invoice:INV000049, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Manola Perfume Oil, Qty:2 X 300, Total:600\n\nName:Manola Hair Oil, Qty:3 X 590, Total:1770\n\nName:Outside Product, Qty:1 X 200, Total:200\n\nName:Conveyance, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-11-17', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(127, '01', 'CHA000049', 'INV000049', '01SV000049', '2024-11-17', 209, 'Cash', 'SV', '5000.00', 'Cr', 'Cash in hand', 'Invoice:INV000049, Cash Received From: Mr. Masum, Through Cash Received Amount:5,000.00 TK Cable, Switch and Plug\n', '0', NULL, NULL, '2024-11-17', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(128, '01', 'CHA000049', 'INV000049', '01SV000049', '2024-11-17', 11, 'Mr. Masum', 'SV', '5000.00', 'Dr', 'Cash in hand', 'Invoice:INV000049, Cash Received From: Mr. Masum, Through Cash Received Amount:5,000.00 TK Cable, Switch and Plug\n', '0', NULL, NULL, '2024-11-17', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(129, '01', 'CHA000050', 'INV000050', '01SV000050', '2024-11-18', 208, 'Sales Account', 'SV', '4150.00', 'Dr', NULL, 'Invoice:INV000050, Name:Manola Men Perfume, Qty:2 X 2000, Total:4000\n\nName:Manola lip gel, Qty:3 X 50, Total:150\n', NULL, NULL, NULL, '2024-11-18', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-18 09:34:29', '2024-11-18 09:34:29'),
(130, '01', 'CHA000050', 'INV000050', '01SV000050', '2024-11-18', 14, 'Mr. Shahed', 'SV', '4150.00', 'Cr', NULL, 'Invoice:INV000050, Name:Manola Men Perfume, Qty:2 X 2000, Total:4000\n\nName:Manola lip gel, Qty:3 X 50, Total:150\n', NULL, NULL, NULL, '2024-11-18', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-18 09:34:29', '2024-11-18 09:34:29'),
(131, '01', 'CHA000050', 'INV000050', '01SV000050', '2024-11-18', 208, 'Cash', 'SV', '4150.00', 'Cr', 'Cash in hand', 'Invoice:INV000050, Cash Received From: Mr. Shahed, Through Cash Received Amount:4,150.00 TK \n', '0', NULL, NULL, '2024-11-18', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-18 09:34:29', '2024-11-18 09:34:29'),
(132, '01', 'CHA000050', 'INV000050', '01SV000050', '2024-11-18', 11, 'Mr. Shahed', 'SV', '4150.00', 'Dr', 'Cash in hand', 'Invoice:INV000050, Cash Received From: Mr. Shahed, Through Cash Received Amount:4,150.00 TK \n', '0', NULL, NULL, '2024-11-18', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-18 09:34:29', '2024-11-18 09:34:29'),
(133, '01', 'CHA000051', 'INV000051', '01SV000051', '2024-11-18', 208, 'Sales Account', 'SV', '2500.00', 'Dr', NULL, 'Invoice:INV000051, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Outside Product, Qty:1 X 200, Total:200\n\nName:Conveyance, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-11-18', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-18 11:15:40', '2024-11-18 11:15:40'),
(134, '01', 'CHA000051', 'INV000051', '01SV000051', '2024-11-18', 14, 'Mr. Shahed', 'SV', '2500.00', 'Cr', NULL, 'Invoice:INV000051, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n\nName:Outside Product, Qty:1 X 200, Total:200\n\nName:Conveyance, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-11-18', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-18 11:15:40', '2024-11-18 11:15:40'),
(135, '01', 'CHA000051', 'INV000051', '01SV000051', '2024-11-18', 208, 'Cash', 'SV', '2500.00', 'Cr', 'Cash in hand', 'Invoice:INV000051, Cash Received From: Mr. Shahed, Through Cash Received Amount:2,500.00 TK Complete Service\n', '0', NULL, NULL, '2024-11-18', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-18 11:15:40', '2024-11-18 11:15:40'),
(136, '01', 'CHA000051', 'INV000051', '01SV000051', '2024-11-18', 11, 'Mr. Shahed', 'SV', '2500.00', 'Dr', 'Cash in hand', 'Invoice:INV000051, Cash Received From: Mr. Shahed, Through Cash Received Amount:2,500.00 TK Complete Service\n', '0', NULL, NULL, '2024-11-18', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-18 11:15:40', '2024-11-18 11:15:40'),
(137, '01', 'CHA000052', 'INV000052', '01SV000052', '2024-11-19', 208, 'Sales Account', 'SV', '1500.00', 'Dr', NULL, 'Invoice:INV000052, Name:Manola Girls Body Perfume, Qty:1 X 1000, Total:1000\n\nName:Outside Product, Qty:1 X 200, Total:200\n\nName:Conveyance, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-11-19', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-19 09:25:50', '2024-11-19 09:25:50'),
(138, '01', 'CHA000052', 'INV000052', '01SV000052', '2024-11-19', 14, 'Mr. Shahed', 'SV', '1500.00', 'Cr', NULL, 'Invoice:INV000052, Name:Manola Girls Body Perfume, Qty:1 X 1000, Total:1000\n\nName:Outside Product, Qty:1 X 200, Total:200\n\nName:Conveyance, Qty:1 X 300, Total:300\n', NULL, NULL, NULL, '2024-11-19', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-19 09:25:50', '2024-11-19 09:25:50'),
(139, '01', 'CHA000052', 'INV000052', '01SV000052', '2024-11-19', 208, 'Cash', 'SV', '1500.00', 'Cr', 'Cash in hand', 'Invoice:INV000052, Cash Received From: Mr. Shahed, Through Cash Received Amount:1,500.00 TK Service done\n', '0', NULL, NULL, '2024-11-19', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-19 09:25:50', '2024-11-19 09:25:50'),
(140, '01', 'CHA000052', 'INV000052', '01SV000052', '2024-11-19', 11, 'Mr. Shahed', 'SV', '1500.00', 'Dr', 'Cash in hand', 'Invoice:INV000052, Cash Received From: Mr. Shahed, Through Cash Received Amount:1,500.00 TK Service done\n', '0', NULL, NULL, '2024-11-19', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-19 09:25:50', '2024-11-19 09:25:50'),
(143, '01', NULL, 'RTS000049', '01SV000054', '2024-11-21', 209, 'Sales Account', 'SR', '2890.00', 'Dr', NULL, 'Invoice No:RTS000049, Name:Manola Men Perfume, Qty:1 X 2000.00, Total:2000\n\nName:Manola Perfume Oil, Qty:1 X 300.00, Total:300\n\nName:Manola Hair Oil, Qty:1 X 590.00, Total:590\n', NULL, NULL, NULL, '2024-11-21', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-21 11:31:02', '2024-11-21 11:31:02'),
(144, '01', NULL, 'RTS000049', '01SV000054', '2024-11-21', 14, 'Mr. Masum', 'SR', '2890.00', 'Cr', NULL, 'Invoice No:RTS000049, Name:Manola Men Perfume, Qty:1 X 2000.00, Total:2000\n\nName:Manola Perfume Oil, Qty:1 X 300.00, Total:300\n\nName:Manola Hair Oil, Qty:1 X 590.00, Total:590\n', NULL, NULL, NULL, '2024-11-21', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-21 11:31:02', '2024-11-21 11:31:02'),
(145, '01', NULL, 'PUR000007', '01PV000010', '2024-11-15', 13, 'Perfume World', 'PV', '8000.00', 'Dr', NULL, 'Invoice No:PUR000007, Name:Manola Men Perfume, Qty:5 X 1600, Total:8000\n', NULL, NULL, NULL, '2024-11-15', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-25 07:10:40', '2024-11-25 07:10:40'),
(146, '01', NULL, 'PUR000007', '01PV000010', '2024-11-15', 207, 'Purchase Account', 'PV', '8000.00', 'Cr', NULL, 'Invoice No:PUR000007, Name:Manola Men Perfume, Qty:5 X 1600, Total:8000\n', NULL, NULL, NULL, '2024-11-15', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-25 07:10:40', '2024-11-25 07:10:40'),
(147, '01', NULL, 'PUR000008', '01PV000011', '2024-11-26', 13, 'Perfume World', 'PV', '33455.00', 'Dr', NULL, 'Invoice No:PUR000008, Name:Manola Men Perfume, Qty:5 X 1600, Total:8000\n\nName:Manola Winter Perfume, Qty:5 X 1500, Total:7500\n\nName:Manola Girls Body Perfume, Qty:5 X 700, Total:3500\n\nName:Manola Mens Body Spray, Qty:5 X 700, Total:3500\n\nName:A3 Paper 68GSM, Qty:5 X 147, Total:735\n\nName:A4 Paper-70GSM, Qty:5 X 184, Total:920\n\nName:Manola Shampoo, Qty:5 X 390, Total:1950\n\nName:Manola Hair Oil, Qty:5 X 480, Total:2400\n\nName:Manola lip gel, Qty:5 X 40, Total:200\n\nName:Manola Body Lotion, Qty:5 X 700, Total:3500\n\nName:Manola Perfume Oil, Qty:5 X 250, Total:1250\n', NULL, NULL, NULL, '2024-11-26', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(148, '01', NULL, 'PUR000008', '01PV000011', '2024-11-26', 207, 'Purchase Account', 'PV', '33455.00', 'Cr', NULL, 'Invoice No:PUR000008, Name:Manola Men Perfume, Qty:5 X 1600, Total:8000\n\nName:Manola Winter Perfume, Qty:5 X 1500, Total:7500\n\nName:Manola Girls Body Perfume, Qty:5 X 700, Total:3500\n\nName:Manola Mens Body Spray, Qty:5 X 700, Total:3500\n\nName:A3 Paper 68GSM, Qty:5 X 147, Total:735\n\nName:A4 Paper-70GSM, Qty:5 X 184, Total:920\n\nName:Manola Shampoo, Qty:5 X 390, Total:1950\n\nName:Manola Hair Oil, Qty:5 X 480, Total:2400\n\nName:Manola lip gel, Qty:5 X 40, Total:200\n\nName:Manola Body Lotion, Qty:5 X 700, Total:3500\n\nName:Manola Perfume Oil, Qty:5 X 250, Total:1250\n', NULL, NULL, NULL, '2024-11-26', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(149, '01', 'CHA000060', 'INV000053', '01SV000055', '2024-11-01', 208, 'Sales Account', 'SV', '10000.00', 'Dr', NULL, 'Invoice:INV000053, Name:Manola Men Perfume, Qty:5 X 2000, Total:10000\n', NULL, NULL, NULL, '2024-11-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-28 06:44:18', '2024-11-28 06:44:18'),
(150, '01', 'CHA000060', 'INV000053', '01SV000055', '2024-11-01', 14, 'Mr. Shahed', 'SV', '10000.00', 'Cr', NULL, 'Invoice:INV000053, Name:Manola Men Perfume, Qty:5 X 2000, Total:10000\n', NULL, NULL, NULL, '2024-11-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-28 06:44:18', '2024-11-28 06:44:18'),
(151, '01', 'CHA000060', 'INV000053', '01SV000055', '2024-11-01', 208, 'Cash', 'SV', '10000.00', 'Cr', 'Cash in hand', 'Invoice:INV000053, Cash Received From: Mr. Shahed, Through Cash Received Amount:10,000.00 TK Remarks\n', '0', NULL, NULL, '2024-11-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-28 06:44:18', '2024-11-28 06:44:18'),
(152, '01', 'CHA000060', 'INV000053', '01SV000055', '2024-11-01', 11, 'Mr. Shahed', 'SV', '10000.00', 'Dr', 'Cash in hand', 'Invoice:INV000053, Cash Received From: Mr. Shahed, Through Cash Received Amount:10,000.00 TK Remarks\n', '0', NULL, NULL, '2024-11-01', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-11-28 06:44:18', '2024-11-28 06:44:18'),
(171, '01', 'CHA000063', 'INV000056', '01SV000060', '2024-12-02', 210, 'Sales Account', 'SV', '2000.00', 'Dr', NULL, 'Invoice:INV000056, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 06:31:45', '2024-12-02 06:31:45'),
(172, '01', 'CHA000063', 'INV000056', '01SV000060', '2024-12-02', 14, 'Md. Masum', 'SV', '2000.00', 'Cr', NULL, 'Invoice:INV000056, Name:Manola Men Perfume, Qty:1 X 2000, Total:2000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 06:31:45', '2024-12-02 06:31:45'),
(173, '01', 'CHA000063', 'INV000056', '01SV000060', '2024-12-02', 210, 'Cash', 'SV', '2000.00', 'Cr', 'Cash in hand', 'Invoice:INV000056, Cash Received From: Md. Masum, Through Cash Received Amount:2,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 06:31:45', '2024-12-02 06:31:45');
INSERT INTO `finance_transactions` (`id`, `company_code`, `delivery_challan_no`, `invoice_no`, `voucher_no`, `voucher_date`, `acid`, `to_acc_name`, `type`, `amount`, `balance_type`, `payment_type`, `narration`, `cheque_no`, `cheque_date`, `cheque_type`, `transaction_date`, `transaction_by`, `invoice_type`, `pending_adjustment`, `status`, `done_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(174, '01', 'CHA000063', 'INV000056', '01SV000060', '2024-12-02', 11, 'Md. Masum', 'SV', '2000.00', 'Dr', 'Cash in hand', 'Invoice:INV000056, Cash Received From: Md. Masum, Through Cash Received Amount:2,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 06:31:45', '2024-12-02 06:31:45'),
(175, '01', 'CHA000064', 'INV000057', '01SV000061', '2024-12-02', 217, 'Sales Account', 'SV', '4000.00', 'Dr', NULL, 'Invoice:INV000057, Name:Manola Men Perfume, Qty:2 X 2000, Total:4000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:31:40', '2024-12-02 07:31:40'),
(176, '01', 'CHA000064', 'INV000057', '01SV000061', '2024-12-02', 14, 'Modern Health Care', 'SV', '4000.00', 'Cr', NULL, 'Invoice:INV000057, Name:Manola Men Perfume, Qty:2 X 2000, Total:4000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:31:40', '2024-12-02 07:31:40'),
(177, '01', 'CHA000064', 'INV000057', '01SV000061', '2024-12-02', 217, 'Cash', 'SV', '4000.00', 'Cr', 'Cash in hand', 'Invoice:INV000057, Cash Received From: Modern Health Care, Through Cash Received Amount:4,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:31:40', '2024-12-02 07:31:40'),
(178, '01', 'CHA000064', 'INV000057', '01SV000061', '2024-12-02', 11, 'Modern Health Care', 'SV', '4000.00', 'Dr', 'Cash in hand', 'Invoice:INV000057, Cash Received From: Modern Health Care, Through Cash Received Amount:4,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:31:40', '2024-12-02 07:31:40'),
(179, '01', 'CHA000065', 'INV000058', '01SV000062', '2024-12-02', 209, 'Sales Account', 'SV', '1000.00', 'Dr', NULL, 'Invoice:INV000058, Name:Manola Body Lotion, Qty:1 X 1000, Total:1000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:33:43', '2024-12-02 07:33:43'),
(180, '01', 'CHA000065', 'INV000058', '01SV000062', '2024-12-02', 14, 'Mr. Masum', 'SV', '1000.00', 'Cr', NULL, 'Invoice:INV000058, Name:Manola Body Lotion, Qty:1 X 1000, Total:1000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:33:43', '2024-12-02 07:33:43'),
(181, '01', 'CHA000065', 'INV000058', '01SV000062', '2024-12-02', 209, 'Cash', 'SV', '1000.00', 'Cr', 'Cash in hand', 'Invoice:INV000058, Cash Received From: Mr. Masum, Through Cash Received Amount:1,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:33:43', '2024-12-02 07:33:43'),
(182, '01', 'CHA000065', 'INV000058', '01SV000062', '2024-12-02', 11, 'Mr. Masum', 'SV', '1000.00', 'Dr', 'Cash in hand', 'Invoice:INV000058, Cash Received From: Mr. Masum, Through Cash Received Amount:1,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:33:43', '2024-12-02 07:33:43'),
(183, '01', 'CHA000066', 'INV000059', '01SV000063', '2024-12-02', 209, 'Sales Account', 'SV', '50.00', 'Dr', NULL, 'Invoice:INV000059, Name:Manola lip gel, Qty:1 X 50, Total:50\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:37:30', '2024-12-02 07:37:30'),
(184, '01', 'CHA000066', 'INV000059', '01SV000063', '2024-12-02', 14, 'Mr. Masum', 'SV', '50.00', 'Cr', NULL, 'Invoice:INV000059, Name:Manola lip gel, Qty:1 X 50, Total:50\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:37:30', '2024-12-02 07:37:30'),
(185, '01', 'CHA000066', 'INV000059', '01SV000063', '2024-12-02', 209, 'Cash', 'SV', '50.00', 'Cr', 'Cash in hand', 'Invoice:INV000059, Cash Received From: Mr. Masum, Through Cash Received Amount:50.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:37:30', '2024-12-02 07:37:30'),
(186, '01', 'CHA000066', 'INV000059', '01SV000063', '2024-12-02', 11, 'Mr. Masum', 'SV', '50.00', 'Dr', 'Cash in hand', 'Invoice:INV000059, Cash Received From: Mr. Masum, Through Cash Received Amount:50.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:37:30', '2024-12-02 07:37:30'),
(187, '01', 'CHA000067', 'INV000060', '01SV000064', '2024-12-02', 209, 'Sales Account', 'SV', '1000.00', 'Dr', NULL, 'Invoice:INV000060, Name:Manola Body Lotion, Qty:1 X 1000, Total:1000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:39:09', '2024-12-02 07:39:09'),
(188, '01', 'CHA000067', 'INV000060', '01SV000064', '2024-12-02', 14, 'Mr. Masum', 'SV', '1000.00', 'Cr', NULL, 'Invoice:INV000060, Name:Manola Body Lotion, Qty:1 X 1000, Total:1000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:39:09', '2024-12-02 07:39:09'),
(189, '01', 'CHA000067', 'INV000060', '01SV000064', '2024-12-02', 209, 'Cash', 'SV', '1000.00', 'Cr', 'Cash in hand', 'Invoice:INV000060, Cash Received From: Mr. Masum, Through Cash Received Amount:1,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:39:09', '2024-12-02 07:39:09'),
(190, '01', 'CHA000067', 'INV000060', '01SV000064', '2024-12-02', 11, 'Mr. Masum', 'SV', '1000.00', 'Dr', 'Cash in hand', 'Invoice:INV000060, Cash Received From: Mr. Masum, Through Cash Received Amount:1,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:39:09', '2024-12-02 07:39:09'),
(191, '01', 'CHA000068', 'INV000061', '01SV000065', '2024-12-02', 208, 'Sales Account', 'SV', '4000.00', 'Dr', NULL, 'Invoice:INV000061, Name:Manola Men Perfume, Qty:2 X 2000, Total:4000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:54:08', '2024-12-02 07:54:08'),
(192, '01', 'CHA000068', 'INV000061', '01SV000065', '2024-12-02', 14, 'Mr. Shahed', 'SV', '4000.00', 'Cr', NULL, 'Invoice:INV000061, Name:Manola Men Perfume, Qty:2 X 2000, Total:4000\n', NULL, NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:54:08', '2024-12-02 07:54:08'),
(193, '01', 'CHA000068', 'INV000061', '01SV000065', '2024-12-02', 208, 'Cash', 'SV', '4000.00', 'Cr', 'Cash in hand', 'Invoice:INV000061, Cash Received From: Mr. Shahed, Through Cash Received Amount:4,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:54:08', '2024-12-02 07:54:08'),
(194, '01', 'CHA000068', 'INV000061', '01SV000065', '2024-12-02', 11, 'Mr. Shahed', 'SV', '4000.00', 'Dr', 'Cash in hand', 'Invoice:INV000061, Cash Received From: Mr. Shahed, Through Cash Received Amount:4,000.00 TK \n', '0', NULL, NULL, '2024-12-02', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-02 07:54:08', '2024-12-02 07:54:08'),
(199, '01', 'CHA000001', 'INV000001', '01SV000001', '2024-10-15', 208, 'Sales Account', 'SV', '13000.00', 'Dr', NULL, 'Invoice:INV000001, Name:Manola Men Perfume, Qty:5.00 X 2000.00, , Total:10000\n\nName:Manola Perfume Oil, Qty:10.00 X 300.00, , Total:3000\n', NULL, NULL, NULL, '2024-10-15', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-11 11:48:09', '2024-12-11 11:48:09'),
(200, '01', 'CHA000001', 'INV000001', '01SV000001', '2024-10-15', 14, 'Mr. Shahed', 'SV', '13000.00', 'Cr', NULL, 'Invoice:INV000001, Name:Manola Men Perfume, Qty:5.00 X 2000.00, , Total:10000\n\nName:Manola Perfume Oil, Qty:10.00 X 300.00, , Total:3000\n', NULL, NULL, NULL, '2024-10-15', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-11 11:48:09', '2024-12-11 11:48:09'),
(201, '01', 'CHA000001', 'INV000001', '01SV000001', '2024-10-15', 208, 'Cash', 'SV', '13000.00', 'Cr', 'Cash in hand', 'Invoice:INV000001, Cash Received From: Mr. Shahed, Through Cash Received Amount:13,000.00 TK Remarks .....\n', NULL, NULL, NULL, '2024-10-15', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-11 11:48:10', '2024-12-11 11:48:10'),
(202, '01', 'CHA000001', 'INV000001', '01SV000001', '2024-10-15', 11, 'Mr. Shahed', 'SV', '13000.00', 'Dr', 'Cash in hand', 'Invoice:INV000001, Cash Received From: Mr. Shahed, Through Cash Received Amount:13,000.00 TK Remarks .....\n', NULL, NULL, NULL, '2024-10-15', 'Demo', NULL, NULL, 0, 'Demo', NULL, '2024-12-11 11:48:10', '2024-12-11 11:48:10');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image`, `created_at`, `updated_at`) VALUES
(10, 'images/gallery/1754475905_68932d81e69c5.jpg', '2025-07-09 10:10:00', '2025-08-06 10:25:05'),
(11, 'images/gallery/1754475936_68932da070f93.jpg', '2025-07-09 10:10:00', '2025-08-06 10:25:36'),
(12, 'images/gallery/1754475949_68932dad349c6.jpg', '2025-07-09 10:10:00', '2025-08-06 10:25:49'),
(13, 'images/gallery/1754475960_68932db8ae4cb.jpg', '2025-07-09 10:10:00', '2025-08-06 10:26:00'),
(14, 'images/gallery/1754475967_68932dbfd6a30.jpg', '2025-07-09 10:10:01', '2025-08-06 10:26:07'),
(15, 'images/gallery/1754475975_68932dc73765b.jpg', '2025-07-09 10:10:01', '2025-08-06 10:26:15'),
(16, 'images/gallery/1752055801_686e3ff95603a.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(17, 'images/gallery/1752055801_686e3ff97e9ed.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(18, 'images/gallery/1752055801_686e3ff9951a7.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(19, 'images/gallery/1752055801_686e3ff99b1d4.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(20, 'images/gallery/1752055801_686e3ff9a1432.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(21, 'images/gallery/1752055801_686e3ff9a7511.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(22, 'images/gallery/1752055801_686e3ff9ad7fe.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(23, 'images/gallery/1752055801_686e3ff9b38f6.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(24, 'images/gallery/1752055801_686e3ff9b9be5.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(25, 'images/gallery/1752055801_686e3ff9c641f.jpg', '2025-07-09 10:10:01', '2025-07-09 10:10:01'),
(26, 'images/gallery/1752410764_6873aa8c63083.jpg', '2025-07-13 12:46:04', '2025-07-13 12:46:04'),
(27, 'images/gallery/1752410764_6873aa8c7990d.jpg', '2025-07-13 12:46:04', '2025-07-13 12:46:04'),
(28, 'images/gallery/1752410764_6873aa8c92ac6.jpg', '2025-07-13 12:46:04', '2025-07-13 12:46:04'),
(29, 'images/gallery/1752410764_6873aa8ca4510.jpg', '2025-07-13 12:46:04', '2025-07-13 12:46:04'),
(30, 'images/gallery/1752410764_6873aa8cb6b6e.jpg', '2025-07-13 12:46:04', '2025-07-13 12:46:04'),
(31, 'images/gallery/1752410764_6873aa8cc7990.jpg', '2025-07-13 12:46:04', '2025-07-13 12:46:04'),
(32, 'images/gallery/1752410764_6873aa8cdb5c0.jpg', '2025-07-13 12:46:04', '2025-07-13 12:46:04'),
(33, 'images/gallery/1752410764_6873aa8cf140c.jpg', '2025-07-13 12:46:04', '2025-07-13 12:46:04'),
(34, 'images/gallery/1752410765_6873aa8d09bb4.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(35, 'images/gallery/1752410765_6873aa8d1aa36.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(36, 'images/gallery/1752410765_6873aa8d20287.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(37, 'images/gallery/1752410765_6873aa8d262ba.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(38, 'images/gallery/1752410765_6873aa8d2ef5a.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(39, 'images/gallery/1752410765_6873aa8d34709.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(40, 'images/gallery/1752410765_6873aa8d3a87b.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(41, 'images/gallery/1752410765_6873aa8d408c3.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(42, 'images/gallery/1752410765_6873aa8d46bd7.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(43, 'images/gallery/1752410765_6873aa8d4ce38.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(44, 'images/gallery/1752410765_6873aa8d5904c.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(45, 'images/gallery/1752410765_6873aa8d6123e.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(46, 'images/gallery/1752410765_6873aa8d7c3e8.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(47, 'images/gallery/1752410765_6873aa8d8bcd7.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(48, 'images/gallery/1752410765_6873aa8d94d02.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(49, 'images/gallery/1752410765_6873aa8da2a9b.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(50, 'images/gallery/1752410765_6873aa8dab821.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(51, 'images/gallery/1752410765_6873aa8db8947.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(52, 'images/gallery/1752410765_6873aa8dc2e5c.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(53, 'images/gallery/1752410765_6873aa8dcd9e9.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(54, 'images/gallery/1752410765_6873aa8dd5459.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(55, 'images/gallery/1752410765_6873aa8ddd2f4.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(56, 'images/gallery/1752410765_6873aa8de35cf.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(57, 'images/gallery/1752410765_6873aa8dec23a.jpg', '2025-07-13 12:46:05', '2025-07-13 12:46:05'),
(62, 'images/gallery/1756277486_68aeaaeecaf16.jpg', '2025-08-27 06:51:26', '2025-08-27 06:51:26'),
(63, 'images/gallery/1756277486_68aeaaeedd416.jpg', '2025-08-27 06:51:26', '2025-08-27 06:51:26'),
(64, 'images/gallery/1756277486_68aeaaeee9840.jpg', '2025-08-27 06:51:26', '2025-08-27 06:51:26'),
(65, 'images/gallery/1756277487_68aeaaef018d4.jpg', '2025-08-27 06:51:27', '2025-08-27 06:51:27'),
(66, 'images/gallery/1756277487_68aeaaef09bb3.jpg', '2025-08-27 06:51:27', '2025-08-27 06:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `genrate_payslip_options`
--

CREATE TABLE `genrate_payslip_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `end_date` date NOT NULL,
  `occasion` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `date`, `end_date`, `occasion`, `created_by`, `created_at`, `updated_at`) VALUES
(6, '2024-01-05', '2024-01-05', 'Christmas Day', 1, '2023-12-18 23:45:52', '2024-01-14 02:54:35');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `income_head` int(11) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `type` tinyint(4) DEFAULT 1,
  `created_by` varchar(120) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_heads`
--

CREATE TABLE `income_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(125) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `income_heads`
--

INSERT INTO `income_heads` (`id`, `name`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'House Rent', 1, 'Demo', '2023-12-17 01:46:11', '2023-12-17 01:46:11'),
(2, 'Medical', 1, 'Demo', '2023-12-17 01:46:40', '2023-12-17 01:46:40'),
(3, 'P/F', 1, 'Demo', '2023-12-17 01:52:22', '2023-12-17 01:55:52'),
(4, 'Others', 1, 'Demo', '2023-12-17 01:56:38', '2023-12-17 02:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `invoiceno`
--

CREATE TABLE `invoiceno` (
  `voucher_no` int(11) DEFAULT NULL,
  `order_no` int(11) DEFAULT NULL,
  `invoice_no_id` int(11) NOT NULL,
  `delivery_challan_no` int(11) DEFAULT NULL,
  `sales_no` int(11) DEFAULT NULL,
  `damage_no` int(11) DEFAULT NULL,
  `purchase_no` int(11) DEFAULT NULL,
  `cr_voucher_no` int(11) DEFAULT NULL,
  `cp_voucher_no` int(11) DEFAULT NULL,
  `quotation_no` int(11) DEFAULT NULL,
  `transfer_no` int(11) DEFAULT NULL,
  `batch_no` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `money_receipt_no` int(11) DEFAULT NULL,
  `delivery_order_no` int(11) DEFAULT NULL,
  `booking_no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoiceno`
--

INSERT INTO `invoiceno` (`voucher_no`, `order_no`, `invoice_no_id`, `delivery_challan_no`, `sales_no`, `damage_no`, `purchase_no`, `cr_voucher_no`, `cp_voucher_no`, `quotation_no`, `transfer_no`, `batch_no`, `branch_id`, `money_receipt_no`, `delivery_order_no`, `booking_no`) VALUES
(66, 9, 1, 69, 62, 6, 9, 13, 1, 1, 5, 4, 1, 1, 1, 73);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `invoice_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `balance_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `late_times`
--

CREATE TABLE `late_times` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `late_time` time DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `late_times`
--

INSERT INTO `late_times` (`id`, `late_time`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '09:37:00', 'Demo', '2024-01-11 00:25:57', '2024-01-14 00:29:20');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `loan_option` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `type` tinyint(4) DEFAULT 1,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `employee_id`, `loan_option`, `title`, `amount`, `percentage`, `type`, `start_date`, `end_date`, `reason`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Allowance Title', 3000.00, '10.00', 2, '2023-11-01', '2023-12-31', 'Loan Reason', 1, '2023-12-16 23:58:07', '2023-12-16 23:58:07');

-- --------------------------------------------------------

--
-- Table structure for table `loan_options`
--

CREATE TABLE `loan_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `loan_options`
--

INSERT INTO `loan_options` (`id`, `name`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Loan 1', 1, 1, '2023-07-13 07:21:21', '2023-07-13 07:21:34'),
(2, 'Loan 3', 1, 1, '2023-07-13 07:21:30', '2023-12-17 04:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `department_id` longtext DEFAULT NULL,
  `employee_id` longtext DEFAULT NULL,
  `title` varchar(191) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `note` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_05_28_090500_add_login_fields_to_users_table', 1),
(6, '2023_06_11_075700_create_permission_tables', 1),
(7, '2023_06_12_013333_add_profile_photo_path_column_to_users_table', 1),
(8, '2024_05_28_185132_create_notifications_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 32),
(2, 'App\\Models\\User', 29),
(2, 'App\\Models\\User', 31),
(2, 'App\\Models\\User', 34),
(3, 'App\\Models\\User', 42),
(3, 'App\\Models\\User', 43),
(7, 'App\\Models\\User', 33),
(8, 'App\\Models\\User', 38),
(9, 'App\\Models\\User', 41),
(10, 'App\\Models\\User', 39),
(11, 'App\\Models\\User', 35),
(12, 'App\\Models\\User', 27),
(12, 'App\\Models\\User', 30);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_salaries`
--

CREATE TABLE `monthly_salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `generate_date` timestamp NULL DEFAULT NULL,
  `salary_month` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(11,2) NOT NULL DEFAULT 0.00,
  `income` decimal(11,2) NOT NULL DEFAULT 0.00,
  `deduction` decimal(11,2) NOT NULL DEFAULT 0.00,
  `net_payble` decimal(11,2) NOT NULL DEFAULT 0.00,
  `status` int(11) NOT NULL,
  `created_by` varchar(120) NOT NULL,
  `approved_by` varchar(120) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `monthly_salaries`
--

INSERT INTO `monthly_salaries` (`id`, `employee_id`, `branch_id`, `generate_date`, `salary_month`, `basic_salary`, `income`, `deduction`, `net_payble`, `status`, `created_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 38, NULL, '2024-03-20 18:00:00', '2024-03', '50000.00', '0.00', '0.00', '0.00', 0, '1', NULL, '2024-03-21 02:28:16', '2024-03-21 02:28:16'),
(2, 39, NULL, '2024-03-20 18:00:00', '2024-03', '55555.00', '0.00', '0.00', '0.00', 0, '1', NULL, '2024-03-21 02:28:16', '2024-03-21 02:28:16'),
(3, 40, NULL, '2024-03-20 18:00:00', '2024-03', '50000.00', '0.00', '0.00', '0.00', 0, '1', NULL, '2024-03-21 02:28:16', '2024-03-21 02:28:16'),
(4, 41, NULL, '2024-03-20 18:00:00', '2024-03', '40000.00', '0.00', '0.00', '0.00', 0, '1', NULL, '2024-03-21 02:28:16', '2024-03-21 02:28:16'),
(5, 38, NULL, '2024-03-20 18:00:00', '2024-02', '50000.00', '0.00', '0.00', '0.00', 0, '1', NULL, '2024-03-21 02:39:32', '2024-03-21 02:39:32'),
(6, 39, NULL, '2024-03-20 18:00:00', '2024-02', '55555.00', '0.00', '0.00', '0.00', 0, '1', NULL, '2024-03-21 02:39:32', '2024-03-21 02:39:32'),
(7, 40, NULL, '2024-03-20 18:00:00', '2024-02', '50000.00', '0.00', '0.00', '0.00', 0, '1', NULL, '2024-03-21 02:39:32', '2024-03-21 02:39:32'),
(8, 41, NULL, '2024-03-20 18:00:00', '2024-02', '40000.00', '0.00', '0.00', '0.00', 0, '1', NULL, '2024-03-21 02:39:32', '2024-03-21 02:39:32');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_salary_details`
--

CREATE TABLE `monthly_salary_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `monthly_salary_id` bigint(20) NOT NULL,
  `head_id` bigint(20) NOT NULL,
  `head_name` varchar(120) NOT NULL,
  `head_type` varchar(120) NOT NULL,
  `amount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `created_by` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `monthly_salary_details`
--

INSERT INTO `monthly_salary_details` (`id`, `monthly_salary_id`, `head_id`, `head_name`, `head_type`, `amount`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'House Rent', 'Income', '20000.00', 'Demo', '2023-12-23 23:34:40', '2023-12-23 23:34:40'),
(2, 1, 1, 'TAX', 'Deduction', '2000.00', 'Demo', '2023-12-23 23:34:40', '2023-12-23 23:34:40'),
(3, 2, 1, 'House Rent', 'Income', '30000.00', 'Demo', '2023-12-23 23:34:41', '2023-12-23 23:34:41'),
(4, 2, 1, 'TAX', 'Deduction', '3000.00', 'Demo', '2023-12-23 23:34:41', '2023-12-23 23:34:41'),
(5, 3, 1, 'House Rent', 'Income', '35000.00', 'Demo', '2023-12-23 23:34:41', '2023-12-23 23:34:41'),
(6, 3, 1, 'TAX', 'Deduction', '3500.00', 'Demo', '2023-12-23 23:34:41', '2023-12-23 23:34:41'),
(7, 4, 1, 'House Rent', 'Income', '20000.00', 'Demo', '2023-12-23 23:35:35', '2023-12-23 23:35:35'),
(8, 4, 1, 'TAX', 'Deduction', '2000.00', 'Demo', '2023-12-23 23:35:35', '2023-12-23 23:35:35'),
(9, 5, 1, 'House Rent', 'Income', '30000.00', 'Demo', '2023-12-23 23:35:35', '2023-12-23 23:35:35'),
(10, 5, 1, 'TAX', 'Deduction', '3000.00', 'Demo', '2023-12-23 23:35:35', '2023-12-23 23:35:35'),
(11, 6, 1, 'House Rent', 'Income', '35000.00', 'Demo', '2023-12-23 23:35:35', '2023-12-23 23:35:35'),
(12, 6, 1, 'TAX', 'Deduction', '3500.00', 'Demo', '2023-12-23 23:35:35', '2023-12-23 23:35:35');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(18,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `title`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Summer Offer', '<p>Benefit from a 10% discount, making your reservations with a minimum of 3 days in advance</p>', '200.00', 'images/offer/23-07-2025-18-14-51-image-1.png', 1, '2025-07-23 11:24:22', '2025-08-04 11:05:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `done_by` varchar(100) DEFAULT NULL,
  `approveby` varchar(100) DEFAULT NULL,
  `file` varchar(500) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` tinyint(10) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_no`, `customer_id`, `order_date`, `delivery_date`, `done_by`, `approveby`, `file`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ORD000001', 208, '2024-10-01', '2024-10-30', 'Demo', NULL, NULL, 'Remarks .....', 0, '2024-10-22 10:48:27', '2024-10-22 10:48:27'),
(2, 'ORD000002', 208, '2024-10-31', '2024-11-20', 'Demo', NULL, NULL, 'Remarks .....', 0, '2024-10-31 09:15:52', '2024-10-31 09:15:52'),
(3, 'ORD000003', 217, '2024-10-31', '2024-11-09', 'Demo', NULL, NULL, 'Remarks .....', 1, '2024-10-31 09:51:03', '2024-10-31 10:35:33'),
(4, 'ORD000004', 209, '2024-11-14', '2024-11-17', 'Demo', 'Demo', NULL, 'Remarks .....', 0, '2024-11-14 10:14:42', '2024-11-21 05:54:41'),
(5, 'ORD000005', 217, '2024-11-26', '2024-11-27', 'Demo', NULL, NULL, NULL, 0, '2024-11-26 07:45:02', '2024-11-26 07:45:02'),
(6, 'ORD000006', 218, '2024-11-26', '2024-11-27', 'Demo', NULL, NULL, 'Order 261124', 0, '2024-11-26 07:47:19', '2024-11-26 07:47:19'),
(7, 'ORD000007', 219, '2024-11-26', '2024-11-28', 'Demo', NULL, NULL, NULL, 0, '2024-11-26 07:48:10', '2024-11-26 07:48:10'),
(8, 'ORD000008', 220, '2024-11-26', '2024-11-28', 'Demo', NULL, NULL, NULL, 0, '2024-11-26 07:50:29', '2024-11-26 07:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `stock_type` varchar(255) DEFAULT NULL,
  `stock_date` datetime DEFAULT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `stock_out_quantity` decimal(18,2) DEFAULT 0.00,
  `stock_out_unit_price` decimal(18,2) DEFAULT 0.00,
  `stock_out_discount` decimal(18,2) DEFAULT 0.00,
  `stock_out_total_amount` decimal(18,2) DEFAULT 0.00,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `purchase_price` decimal(18,2) DEFAULT 0.00,
  `supplier_invoice_no` varchar(255) DEFAULT NULL,
  `stock_in_quantity` decimal(18,2) DEFAULT 0.00,
  `stock_in_unit_price` decimal(18,2) DEFAULT 0.00,
  `stock_in_discount` decimal(18,2) DEFAULT 0.00,
  `stock_in_total_amount` decimal(18,2) DEFAULT 0.00,
  `done_by` varchar(100) DEFAULT NULL,
  `approveby` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `stock_type`, `stock_date`, `order_no`, `customer_id`, `product_id`, `stock_out_quantity`, `stock_out_unit_price`, `stock_out_discount`, `stock_out_total_amount`, `supplier_id`, `purchase_price`, `supplier_invoice_no`, `stock_in_quantity`, `stock_in_unit_price`, `stock_in_discount`, `stock_in_total_amount`, `done_by`, `approveby`, `remarks`, `order_date`, `delivery_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Order', '2024-10-22 16:48:27', 'ORD000001', 208, 1, '5.00', '2000.00', NULL, '10000.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Remarks .....', '2024-10-01', '2024-10-30', 0, '2024-10-22 10:48:27', '2024-10-22 10:48:27'),
(2, 2, 'Order', '2024-10-31 15:15:52', 'ORD000001', 208, 1, '1.00', '2000.00', NULL, '2000.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Remarks .....', '2024-10-31', '2024-11-20', 0, '2024-10-31 09:15:52', '2024-10-31 09:15:52'),
(3, 2, 'Order', '2024-10-31 15:15:52', 'ORD000001', 208, 2, '5.00', '300.00', NULL, '1500.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Remarks .....', '2024-10-31', '2024-11-20', 0, '2024-10-31 09:15:52', '2024-10-31 09:15:52'),
(4, 3, 'Order', '2024-10-31 15:51:03', 'ORD000003', 217, 3, '5.00', '1000.00', NULL, '5000.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Remarks .....', '2024-10-31', '2024-11-09', 0, '2024-10-31 09:51:03', '2024-10-31 09:51:03'),
(5, 4, 'Order', '2024-11-14 16:14:42', 'ORD000004', 209, 1, '5.00', '2000.00', NULL, '10000.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Remarks .....', '2024-11-14', '2024-11-17', 0, '2024-11-14 10:14:42', '2024-11-21 05:54:41'),
(6, 4, 'Order', '2024-11-14 16:14:42', 'ORD000004', 209, 2, '2.00', '300.00', NULL, '600.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Remarks .....', '2024-11-14', '2024-11-17', 0, '2024-11-14 10:14:42', '2024-11-21 05:54:41'),
(7, 4, 'Order', '2024-11-14 16:14:42', 'ORD000004', 209, 3, '1.00', '1000.00', NULL, '1000.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Remarks .....', '2024-11-14', '2024-11-17', 0, '2024-11-14 10:14:42', '2024-11-21 05:54:41'),
(8, 4, 'Order', '2024-11-14 16:14:42', 'ORD000004', 209, 5, '6.00', '590.00', NULL, '3540.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Remarks .....', '2024-11-14', '2024-11-17', 0, '2024-11-14 10:14:42', '2024-11-21 05:54:41'),
(9, 5, 'Order', '2024-11-26 13:45:02', 'ORD000005', 217, 1, '2.00', '2000.00', NULL, '4000.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, NULL, '2024-11-26', '2024-11-27', 0, '2024-11-26 07:45:02', '2024-11-26 07:45:02'),
(10, 5, 'Order', '2024-11-26 13:45:02', 'ORD000005', 217, 2, '3.00', '300.00', NULL, '900.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, NULL, '2024-11-26', '2024-11-27', 0, '2024-11-26 07:45:02', '2024-11-26 07:45:02'),
(11, 6, 'Order', '2024-11-26 13:47:19', 'ORD000006', 218, 1, '5.00', '2000.00', NULL, '10000.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Order 261124', '2024-11-26', '2024-11-27', 0, '2024-11-26 07:47:19', '2024-11-26 07:47:19'),
(12, 6, 'Order', '2024-11-26 13:47:19', 'ORD000006', 218, 2, '6.00', '300.00', NULL, '1800.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 'Order 261124', '2024-11-26', '2024-11-27', 0, '2024-11-26 07:47:19', '2024-11-26 07:47:19'),
(13, 7, 'Order', '2024-11-26 13:48:10', 'ORD000007', 219, 6, '7.00', '480.00', NULL, '3360.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, NULL, '2024-11-26', '2024-11-28', 0, '2024-11-26 07:48:10', '2024-11-26 07:48:10'),
(14, 7, 'Order', '2024-11-26 13:48:10', 'ORD000007', 219, 7, '10.00', '152.73', NULL, '1527.30', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, NULL, '2024-11-26', '2024-11-28', 0, '2024-11-26 07:48:10', '2024-11-26 07:48:10'),
(15, 8, 'Order', '2024-11-26 13:50:29', 'ORD000008', 220, 8, '8.00', '151.69', NULL, '1213.52', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, NULL, '2024-11-26', '2024-11-28', 0, '2024-11-26 07:50:29', '2024-11-26 07:50:29'),
(16, 8, 'Order', '2024-11-26 13:50:29', 'ORD000008', 220, 10, '9.00', '1000.00', NULL, '9000.00', NULL, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, NULL, '2024-11-26', '2024-11-28', 0, '2024-11-26 07:50:29', '2024-11-26 07:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `other_payments`
--

CREATE TABLE `other_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `type` tinyint(4) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `overtimes`
--

CREATE TABLE `overtimes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `number_of_days` int(11) NOT NULL,
  `hours` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `banner_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'images/banner/13-07-2025-17-43-12-about_us_title.jpg', 1, '2025-07-13 11:43:12', '2025-07-13 11:43:12'),
(2, 'Gallery', 'images/banner/13-08-2025-17-32-19-638831.jpg', 1, '2025-07-13 12:21:23', '2025-08-13 11:32:19'),
(3, 'Video', 'images/banner/13-07-2025-18-27-21-gallery_title.jpg', 1, '2025-07-13 12:27:21', '2025-07-13 12:27:21'),
(4, 'Contact', 'images/banner/13-07-2025-18-30-24-gallery_title.jpg', 1, '2025-07-13 12:30:24', '2025-07-13 12:30:24'),
(5, 'Blog', 'images/banner/17-07-2025-16-16-22-13-07-2025-18-30-24-gallery_title.jpg', 1, '2025-07-17 10:16:22', '2025-07-17 10:16:22'),
(6, 'Rooms', 'images/banner/22-07-2025-18-34-44-gallery_title.jpg', 1, '2025-07-22 12:34:44', '2025-07-22 12:34:44'),
(7, 'Our Rides', 'images/banner/22-07-2025-18-35-26-gallery_title.jpg', 1, '2025-07-22 12:35:26', '2025-07-22 12:35:26'),
(8, 'Picnic Spots', 'images/banner/04-08-2025-17-19-17-gallery_title.jpg', 1, '2025-08-04 11:19:17', '2025-08-04 11:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('demo@demo.com', '$2y$10$CuomebRGUjTz4s77BxAkIO9M5la/hLsb1edccHiXfk4SqFcRmhP8O', '2023-11-07 03:01:43'),
('admin@seml.com', '$2y$10$xwSL74bXDWolU0JsTo9nOesNwv9Nc4/.ZTpaUTg19rX.G5yg303pK', '2023-11-07 03:48:36'),
('masumbdonly@gmail.com', '$2y$10$rQbP9HMNPirpjD7y643tI.K7BhSZVLIsE0Qr3SaqxoDAfxgl8S9Ui', '2023-12-24 01:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL,
  `BookingID` int(11) NOT NULL,
  `PaymentDate` datetime NOT NULL,
  `PaymentMode` enum('Cash','Card','Online') DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `PaymentStatus` enum('Pending','Completed','Refunded') DEFAULT 'Pending',
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` int(11) NOT NULL,
  `booking_no` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`id`, `booking_no`, `amount`, `created_at`, `updated_at`) VALUES
(1, 'BO000001', 1500, '2025-02-09 07:48:49', '2025-02-09 07:48:49'),
(2, 'BO000002', 1200, '2025-02-09 07:49:58', '2025-02-09 07:49:58'),
(3, 'BO000004', 500, '2025-02-09 09:16:03', '2025-02-09 09:16:03'),
(4, 'BO000005', 2500, '2025-02-09 09:44:33', '2025-02-09 09:44:33'),
(5, 'BO000009', 1250, '2025-02-09 12:23:01', '2025-02-09 12:23:01'),
(6, 'BO000009', 10000, '2025-02-09 12:23:39', '2025-02-09 12:23:39'),
(7, 'BO000010', 520, '2025-02-10 05:28:09', '2025-02-10 05:28:09'),
(8, 'BO000014', 5000, '2025-02-13 12:04:22', '2025-02-13 12:04:22'),
(9, 'BO000018', 1200, '2025-02-16 11:15:43', '2025-02-16 11:15:43'),
(10, 'BO000020', 500, '2025-05-26 11:38:27', '2025-05-26 11:38:27'),
(11, 'BO000029', 0, '2025-07-24 06:09:18', '2025-07-24 06:09:18'),
(12, 'BO000030', 0, '2025-07-24 06:12:32', '2025-07-24 06:12:32'),
(13, NULL, 0, '2025-07-24 06:13:50', '2025-07-24 06:13:50'),
(14, 'BO000032', 0, '2025-07-24 06:17:11', '2025-07-24 06:17:11'),
(15, 'BO000033', 0, '2025-07-24 07:53:01', '2025-07-24 07:53:01'),
(16, 'BO000034', 0, '2025-07-24 09:03:49', '2025-07-24 09:03:49'),
(17, 'BO000035', 0, '2025-07-24 09:37:16', '2025-07-24 09:37:16'),
(18, 'BO000036', 0, '2025-07-24 09:43:02', '2025-07-24 09:43:02'),
(19, 'BO000037', 0, '2025-07-24 09:45:10', '2025-07-24 09:45:10'),
(20, 'BO000038', 0, '2025-07-24 09:49:30', '2025-07-24 09:49:30'),
(21, 'BO000039', 0, '2025-07-24 09:52:20', '2025-07-24 09:52:20'),
(22, 'BO000040', 0, '2025-07-24 09:58:19', '2025-07-24 09:58:19'),
(23, 'BO000041', 0, '2025-07-24 09:59:31', '2025-07-24 09:59:31'),
(24, 'BO000043', 0, '2025-07-24 10:09:50', '2025-07-24 10:09:50'),
(25, 'BO000044', 0, '2025-07-24 10:14:52', '2025-07-24 10:14:52'),
(26, 'BO000045', 0, '2025-07-24 10:16:56', '2025-07-24 10:16:56'),
(27, 'BO000046', 0, '2025-07-24 10:21:30', '2025-07-24 10:21:30'),
(28, 'BO000047', 0, '2025-07-24 10:36:04', '2025-07-24 10:36:04'),
(29, 'BO000048', 0, '2025-07-24 10:37:32', '2025-07-24 10:37:32'),
(30, 'BO000049', 0, '2025-07-24 10:38:11', '2025-07-24 10:38:11'),
(31, 'BO000050', 0, '2025-07-24 10:39:53', '2025-07-24 10:39:53'),
(32, 'BO000051', 0, '2025-07-24 10:50:07', '2025-07-24 10:50:07'),
(33, 'BO000052', 0, '2025-07-24 10:57:51', '2025-07-24 10:57:51'),
(34, 'BO000053', 0, '2025-07-24 10:59:18', '2025-07-24 10:59:18'),
(35, 'BO000054', 0, '2025-07-24 11:02:15', '2025-07-24 11:02:15'),
(36, 'BO000055', 0, '2025-07-24 11:04:20', '2025-07-24 11:04:20'),
(37, 'BO000066', 0, '2025-07-24 12:48:06', '2025-07-24 12:48:06'),
(38, 'BO000067', 0, '2025-07-24 12:56:52', '2025-07-24 12:56:52'),
(39, 'BO000067', 17000, '2025-07-24 12:57:43', '2025-07-24 12:57:43');

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(20) NOT NULL,
  `payroll_head_id` int(20) NOT NULL,
  `effective_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(125) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `employee_id`, `payroll_head_id`, `effective_date`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 38, 3, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:57', '2024-04-04 00:33:57'),
(2, 38, 4, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:57', '2024-04-04 00:33:57'),
(3, 38, 5, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:57', '2024-04-04 00:33:57'),
(7, 39, 3, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:57', '2024-04-04 00:33:57'),
(8, 39, 4, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:58', '2024-04-04 00:33:58'),
(9, 39, 5, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:58', '2024-04-04 00:33:58'),
(13, 40, 3, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:58', '2024-04-04 00:33:58'),
(14, 40, 4, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:58', '2024-04-04 00:33:58'),
(15, 40, 5, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:58', '2024-04-04 00:33:58'),
(16, 40, 6, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:58', '2024-04-04 00:33:58'),
(17, 40, 7, '2024-04-04', NULL, 'Demo', '2024-04-04 00:33:58', '2024-04-04 00:33:58'),
(19, 44, 3, '2024-03-01', NULL, 'Demo', '2024-04-17 04:45:11', '2024-04-17 04:45:11'),
(20, 44, 4, '2024-03-01', NULL, 'Demo', '2024-04-17 04:45:11', '2024-04-17 04:45:11'),
(21, 44, 5, '2024-03-01', NULL, 'Demo', '2024-04-17 04:45:11', '2024-04-17 04:45:11'),
(22, 44, 6, '2024-03-01', NULL, 'Demo', '2024-04-17 04:45:11', '2024-04-17 04:45:11'),
(23, 44, 7, '2024-03-01', NULL, 'Demo', '2024-04-17 04:45:11', '2024-04-17 04:45:11'),
(24, 44, 8, '2024-03-01', NULL, 'Demo', '2024-04-17 04:45:11', '2024-04-17 04:45:11'),
(25, 43, 3, '2024-03-01', NULL, 'Demo', '2024-04-17 04:46:25', '2024-04-17 04:46:25'),
(26, 43, 4, '2024-03-01', NULL, 'Demo', '2024-04-17 04:46:25', '2024-04-17 04:46:25'),
(27, 43, 5, '2024-03-01', NULL, 'Demo', '2024-04-17 04:46:25', '2024-04-17 04:46:25'),
(28, 43, 6, '2024-03-01', NULL, 'Demo', '2024-04-17 04:46:25', '2024-04-17 04:46:25'),
(29, 43, 7, '2024-03-01', NULL, 'Demo', '2024-04-17 04:46:25', '2024-04-17 04:46:25'),
(30, 43, 8, '2024-03-01', NULL, 'Demo', '2024-04-17 04:46:25', '2024-04-17 04:46:25');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_formulas`
--

CREATE TABLE `payroll_formulas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_head` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `formula` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(125) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_formulas`
--

INSERT INTO `payroll_formulas` (`id`, `payroll_head`, `formula`, `remarks`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(3, '2', '{Basic Salary}/100*10', 'Remarks', 1, 'Demo', '2024-01-02 03:50:05', '2024-01-02 03:55:34'),
(4, '6', '{Basic Salary}/100*10', 'Remarks', 1, 'Demo', '2024-01-02 03:56:11', '2024-04-17 05:53:48'),
(6, '5', '500', 'Paid', 1, 'Demo', '2024-01-02 04:08:04', '2024-01-02 04:08:04'),
(7, '7', '{Basic Salary}/100*30', 'Done', 1, 'Demo', '2024-01-02 04:11:12', '2024-01-02 04:11:12'),
(8, '8', '200', 'Remarks..', 1, 'Demo', '2024-01-02 04:11:59', '2024-01-02 04:11:59'),
(9, '3', '{Basic Salary}/100*35', 'Remarks', 1, 'Demo', '2024-01-02 04:16:36', '2024-01-02 04:18:12'),
(10, '4', '{Basic Salary}', NULL, 1, 'Demo', '2024-04-03 23:35:14', '2024-04-03 23:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_heads`
--

CREATE TABLE `payroll_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` varchar(125) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_heads`
--

INSERT INTO `payroll_heads` (`id`, `name`, `type`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Attendance Bonus', 'Income', 1, 'Demo', '2024-02-04 03:17:24', '2024-05-30 06:18:22'),
(3, 'Over Time', 'Income', 1, 'Demo', '2024-02-04 03:49:34', '2024-02-04 03:49:34'),
(4, 'Mobile Allow', 'Income', 1, 'Demo', '2024-02-04 03:50:14', '2024-02-04 03:50:14'),
(5, 'Night Allow', 'Income', 1, 'Demo', '2024-02-04 03:50:30', '2024-02-04 03:50:30'),
(6, 'Income tax', 'Deduction', 1, 'Demo', '2024-02-04 03:51:37', '2024-02-04 03:51:37'),
(7, 'Provident Fund', 'Deduction', 1, 'Demo', '2024-02-04 03:51:58', '2024-02-04 03:51:58'),
(8, 'Bus Using', 'Deduction', 1, 'Demo', '2024-02-04 03:52:21', '2024-02-04 03:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `payslip_types`
--

CREATE TABLE `payslip_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `payslip_types`
--

INSERT INTO `payslip_types` (`id`, `name`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Monthly', 1, 1, '2023-07-13 07:16:54', '2023-07-13 07:18:53'),
(2, 'Hourly', 1, 1, '2023-07-13 07:18:47', '2023-07-16 05:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `pay_slips`
--

CREATE TABLE `pay_slips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `net_payble` int(11) NOT NULL,
  `salary_month` varchar(191) NOT NULL,
  `status` int(11) NOT NULL,
  `basic_salary` decimal(11,2) NOT NULL DEFAULT 0.00,
  `allowance` text NOT NULL,
  `commission` text NOT NULL,
  `loan` text NOT NULL,
  `other_payment` text NOT NULL,
  `overtime` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pay_slips`
--

INSERT INTO `pay_slips` (`id`, `employee_id`, `net_payble`, `salary_month`, `status`, `basic_salary`, `allowance`, `commission`, `loan`, `other_payment`, `overtime`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 2, 51500, '2023-07', 0, '50000.00', '1500', '0', '0', '0', '0', 1, '2023-12-17 00:04:36', '2023-12-17 00:04:36'),
(3, 3, 50000, '2023-07', 0, '50000.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:04:36', '2023-12-17 00:04:36'),
(5, 1, 73600, '2023-11', 0, '26000.00', '42600', '8000', '3000', '0', '0', 1, '2023-12-17 00:22:21', '2023-12-17 00:22:21'),
(6, 2, 51500, '2023-11', 0, '50000.00', '1500', '0', '0', '0', '0', 1, '2023-12-17 00:22:21', '2023-12-17 00:22:21'),
(7, 3, 50000, '2023-11', 0, '50000.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:21', '2023-12-17 00:22:21'),
(8, 4, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:21', '2023-12-17 00:22:21'),
(9, 5, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:21', '2023-12-17 00:22:21'),
(10, 6, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:21', '2023-12-17 00:22:21'),
(11, 7, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:21', '2023-12-17 00:22:21'),
(12, 8, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:21', '2023-12-17 00:22:21'),
(13, 10, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:22', '2023-12-17 00:22:22'),
(14, 11, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:22', '2023-12-17 00:22:22'),
(15, 12, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:22', '2023-12-17 00:22:22'),
(16, 13, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:22', '2023-12-17 00:22:22'),
(17, 14, 0, '2023-11', 0, '0.00', '0', '0', '0', '0', '0', 1, '2023-12-17 00:22:22', '2023-12-17 00:22:22');

-- --------------------------------------------------------

--
-- Table structure for table `performance_types`
--

CREATE TABLE `performance_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `performance_name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `performance_types`
--

INSERT INTO `performance_types` (`id`, `performance_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Performance 1', 1, '2023-05-22 11:13:44', '2023-05-22 11:26:12'),
(2, 'Performance 2', 1, '2023-05-23 05:06:32', '2023-10-15 23:28:52'),
(3, 'Performance 3', 1, '2023-05-23 10:58:26', '2023-05-23 10:58:26'),
(4, 'Performance 4', 0, '2023-06-05 06:37:17', '2023-10-15 23:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'read user management', 'web', '2023-07-25 04:29:51', '2023-07-25 04:29:51'),
(2, 'write user management', 'web', '2023-07-25 04:29:51', '2023-07-25 04:29:51'),
(3, 'create user management', 'web', '2023-07-25 04:29:51', '2023-07-25 04:29:51'),
(4, 'read content management', 'web', '2023-07-25 04:29:51', '2023-07-25 04:29:51'),
(5, 'write content management', 'web', '2023-07-25 04:29:51', '2023-07-25 04:29:51'),
(6, 'create content management', 'web', '2023-07-25 04:29:51', '2023-07-25 04:29:51'),
(7, 'read financial management', 'web', '2023-07-25 04:29:51', '2023-07-25 04:29:51'),
(8, 'write financial management', 'web', '2023-07-25 04:29:51', '2023-07-25 04:29:51'),
(9, 'create financial management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(10, 'read reporting', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(11, 'write reporting', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(12, 'create reporting', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(13, 'read payroll', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(14, 'write payroll', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(15, 'create payroll', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(16, 'read disputes management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(17, 'write disputes management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(18, 'create disputes management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(19, 'read api controls', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(20, 'write api controls', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(21, 'create api controls', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(22, 'read database management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(23, 'write database management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(24, 'create database management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(25, 'read repository management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(26, 'write repository management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(27, 'create repository management', 'web', '2023-07-25 04:29:52', '2023-07-25 04:29:52'),
(40, 'read employee branch', 'web', '2023-07-26 23:25:05', '2023-07-26 23:25:05'),
(41, 'write employee branch', 'web', '2023-07-26 23:44:52', '2023-07-26 23:44:52'),
(42, 'create employee branch', 'web', '2023-07-26 23:45:04', '2023-07-26 23:45:04'),
(49, 'read employee department', 'web', '2023-07-27 00:22:23', '2023-07-27 00:22:23'),
(50, 'write employee department', 'web', '2023-07-27 00:22:32', '2023-07-27 00:22:32'),
(51, 'create employee department', 'web', '2023-07-27 00:22:41', '2023-07-27 00:22:41'),
(52, 'read employee designation', 'web', '2023-07-27 00:29:15', '2023-07-27 00:29:15'),
(53, 'write employee designation', 'web', '2023-07-27 00:29:28', '2023-07-27 00:29:28'),
(54, 'create employee designation', 'web', '2023-07-27 00:29:38', '2023-07-27 00:29:38'),
(55, 'read employee', 'web', '2023-07-27 00:56:55', '2023-07-27 00:56:55'),
(56, 'write employee', 'web', '2023-07-27 00:57:03', '2023-07-27 00:57:03'),
(57, 'create employee', 'web', '2023-07-27 00:57:30', '2023-07-27 00:57:30'),
(73, 'read employee ledger', 'web', '2023-07-29 23:23:46', '2023-07-29 23:23:46'),
(74, 'create employee ledger', 'web', '2023-07-29 23:24:15', '2023-07-29 23:24:15'),
(80, 'read leave setting', 'web', '2023-07-30 00:25:29', '2023-07-30 00:25:29'),
(81, 'create leave setting', 'web', '2023-07-30 00:25:38', '2023-07-30 00:25:38'),
(82, 'read leave entry', 'web', '2023-07-30 00:44:06', '2023-07-30 00:44:06'),
(83, 'create leave entry', 'web', '2023-07-30 00:44:17', '2023-07-30 00:44:17'),
(86, 'read leave approved department', 'web', '2023-07-30 01:11:47', '2023-07-30 01:11:47'),
(87, 'create leave approved department', 'web', '2023-07-30 01:11:57', '2023-07-30 01:11:57'),
(90, 'read leave approved hr', 'web', '2023-07-30 01:17:04', '2023-07-30 01:17:04'),
(91, 'create leave approved hr', 'web', '2023-07-30 01:17:21', '2023-07-30 01:17:21'),
(93, 'read leave approved management', 'web', '2023-07-30 02:22:41', '2023-07-30 02:22:41'),
(94, 'create leave approved management', 'web', '2023-07-30 02:22:50', '2023-07-30 02:22:50'),
(95, 'read delayin earlyout', 'web', '2023-07-30 03:13:33', '2023-07-30 03:13:33'),
(96, 'write delayin earlyout', 'web', '2023-07-30 03:13:46', '2023-07-30 03:13:46'),
(97, 'create delayin earlyout', 'web', '2023-07-30 03:14:03', '2023-07-30 03:14:03'),
(99, 'read performance type', 'web', '2023-07-30 04:28:35', '2023-07-30 04:28:35'),
(100, 'write performance type', 'web', '2023-07-30 04:28:43', '2023-07-30 04:28:43'),
(101, 'create performance type', 'web', '2023-07-30 04:28:53', '2023-07-30 04:28:53'),
(102, 'read employee performance', 'web', '2023-07-30 04:48:46', '2023-07-30 04:48:46'),
(103, 'write employee performance', 'web', '2023-07-30 04:48:54', '2023-07-30 04:48:54'),
(104, 'create employee performance', 'web', '2023-07-30 04:49:02', '2023-07-30 04:49:02'),
(109, 'read payslip type', 'web', '2023-07-31 01:26:05', '2023-07-31 01:26:05'),
(110, 'write payslip type', 'web', '2023-07-31 01:26:19', '2023-07-31 01:26:19'),
(111, 'create payslip type', 'web', '2023-07-31 01:26:28', '2023-07-31 01:26:28'),
(112, 'read allowance option', 'web', '2023-07-31 01:34:39', '2023-07-31 01:34:39'),
(113, 'write allowance option', 'web', '2023-07-31 01:34:48', '2023-07-31 01:34:48'),
(114, 'create allowance option', 'web', '2023-07-31 01:34:55', '2023-07-31 01:34:55'),
(115, 'read loan option', 'web', '2023-07-31 01:50:51', '2023-07-31 01:50:51'),
(116, 'write loan option', 'web', '2023-07-31 01:51:00', '2023-07-31 01:51:00'),
(117, 'create loan option', 'web', '2023-07-31 01:51:08', '2023-07-31 01:51:08'),
(118, 'read promotion', 'web', '2023-07-31 03:02:51', '2023-07-31 03:02:51'),
(119, 'write promotion', 'web', '2023-07-31 03:03:10', '2023-07-31 03:03:10'),
(120, 'create promotion', 'web', '2023-07-31 03:03:34', '2023-07-31 03:03:34'),
(121, 'read set salaries', 'web', '2023-07-31 03:08:28', '2023-07-31 03:08:28'),
(122, 'write set salaries', 'web', '2023-07-31 03:08:37', '2023-07-31 03:08:37'),
(123, 'create set salaries', 'web', '2023-07-31 03:08:45', '2023-07-31 03:08:45'),
(124, 'read payslips', 'web', '2023-07-31 03:11:20', '2023-07-31 03:11:20'),
(125, 'write payslips', 'web', '2023-07-31 03:11:28', '2023-07-31 03:11:28'),
(126, 'create payslips', 'web', '2023-07-31 03:11:39', '2023-07-31 03:11:39'),
(127, 'read resignation', 'web', '2023-07-31 04:39:44', '2023-07-31 04:39:44'),
(128, 'write resignation', 'web', '2023-07-31 04:40:03', '2023-07-31 04:40:03'),
(129, 'create resignation', 'web', '2023-07-31 04:40:12', '2023-07-31 04:40:12'),
(131, 'read termination', 'web', '2023-07-31 04:54:50', '2023-07-31 04:54:50'),
(132, 'write termination', 'web', '2023-07-31 04:55:04', '2023-07-31 04:55:04'),
(133, 'create termination', 'web', '2023-07-31 04:55:22', '2023-07-31 04:55:22'),
(134, 'read announcement', 'web', '2023-07-31 05:27:17', '2023-07-31 05:27:17'),
(135, 'write announcement', 'web', '2023-07-31 05:27:37', '2023-07-31 05:27:37'),
(136, 'create announcement', 'web', '2023-07-31 05:27:54', '2023-07-31 05:27:54'),
(137, 'read holiday', 'web', '2023-07-31 05:44:45', '2023-07-31 05:44:45'),
(138, 'write holiday', 'web', '2023-07-31 05:45:10', '2023-07-31 05:45:10'),
(139, 'create holiday', 'web', '2023-07-31 05:45:27', '2023-07-31 05:45:27'),
(140, 'read meeting', 'web', '2023-07-31 05:58:53', '2023-07-31 05:58:53'),
(141, 'write meeting', 'web', '2023-07-31 05:59:12', '2023-07-31 05:59:12'),
(142, 'create meeting', 'web', '2023-07-31 05:59:25', '2023-07-31 05:59:25'),
(143, 'read dbbackup', 'web', '2023-07-31 23:43:15', '2023-07-31 23:43:15'),
(144, 'write dbbackup', 'web', '2023-07-31 23:43:33', '2023-07-31 23:43:33'),
(145, 'create dbbackup', 'web', '2023-07-31 23:43:50', '2023-07-31 23:43:50'),
(153, 'read attendance', 'web', '2023-08-02 04:41:21', '2023-08-02 04:41:21'),
(154, 'write attendance', 'web', '2023-08-02 04:41:36', '2023-08-02 04:41:36'),
(155, 'create attendance', 'web', '2023-08-02 04:41:51', '2023-08-02 04:41:51'),
(156, 'read income head', 'web', '2023-12-17 01:59:34', '2023-12-17 01:59:34'),
(157, 'write income head', 'web', '2023-12-17 01:59:43', '2023-12-17 01:59:43'),
(158, 'create income head', 'web', '2023-12-17 01:59:52', '2023-12-17 01:59:52'),
(159, 'read deduction head', 'web', '2023-12-17 03:27:23', '2023-12-17 03:27:23'),
(160, 'write deduction head', 'web', '2023-12-17 03:27:45', '2023-12-17 03:27:45'),
(161, 'create deduction head', 'web', '2023-12-17 03:27:55', '2023-12-17 03:27:55'),
(162, 'read monthly salary', 'web', '2023-12-21 04:11:13', '2023-12-21 04:11:13'),
(163, 'write monthly salary', 'web', '2023-12-21 04:11:27', '2023-12-21 04:11:27'),
(164, 'create monthly salary', 'web', '2023-12-21 04:11:34', '2023-12-21 04:11:34'),
(165, 'read timetable', 'web', '2024-01-15 04:30:48', '2024-01-15 04:30:48'),
(166, 'write timetable', 'web', '2024-01-15 04:30:58', '2024-01-15 04:30:58'),
(167, 'create timetable', 'web', '2024-01-15 04:31:06', '2024-01-15 04:31:06'),
(168, 'write supplier', 'web', '2024-01-21 04:00:36', '2024-01-21 04:00:36'),
(169, 'read supplier', 'web', '2024-01-21 04:01:10', '2024-01-21 04:01:10'),
(170, 'create supplier', 'web', '2024-01-21 04:01:17', '2024-01-21 04:01:17'),
(171, 'read supplier ledger', 'web', '2024-01-21 04:12:19', '2024-01-21 04:12:19'),
(172, 'write supplier ledger', 'web', '2024-01-21 04:12:36', '2024-01-21 04:12:36'),
(173, 'create supplier ledger', 'web', '2024-01-21 04:12:42', '2024-01-21 04:12:42'),
(174, 'read customer', 'web', '2024-01-21 04:15:31', '2024-01-21 04:15:31'),
(175, 'write customer', 'web', '2024-01-21 04:15:41', '2024-01-21 04:15:41'),
(176, 'create customer', 'web', '2024-01-21 04:15:46', '2024-01-21 04:15:46'),
(177, 'read customer ledger', 'web', '2024-01-21 04:15:57', '2024-01-21 04:15:57'),
(178, 'write customer ledger', 'web', '2024-01-21 04:16:03', '2024-01-21 04:16:03'),
(179, 'create customer ledger', 'web', '2024-01-21 04:16:13', '2024-01-21 04:16:13'),
(180, 'read category', 'web', '2024-01-21 04:21:25', '2024-01-21 04:21:25'),
(181, 'write category', 'web', '2024-01-21 04:21:33', '2024-01-21 04:21:33'),
(182, 'create category', 'web', '2024-01-21 04:21:39', '2024-01-21 04:21:39'),
(183, 'read sub category', 'web', '2024-01-21 04:21:54', '2024-01-21 04:21:54'),
(184, 'write sub category', 'web', '2024-01-21 04:22:00', '2024-01-21 04:22:00'),
(185, 'create sub category', 'web', '2024-01-21 04:22:05', '2024-01-21 04:22:05'),
(186, 'read brand', 'web', '2024-01-21 04:22:19', '2024-01-21 04:22:19'),
(187, 'write brand', 'web', '2024-01-21 04:22:24', '2024-01-21 04:22:24'),
(188, 'create brand', 'web', '2024-01-21 04:22:28', '2024-01-21 04:22:28'),
(189, 'read color', 'web', '2024-01-21 04:22:50', '2024-01-21 04:22:50'),
(190, 'write color', 'web', '2024-01-21 04:22:57', '2024-01-21 04:22:57'),
(191, 'create color', 'web', '2024-01-21 04:23:02', '2024-01-21 04:23:02'),
(192, 'read size', 'web', '2024-01-21 04:23:14', '2024-01-21 04:23:14'),
(193, 'write size', 'web', '2024-01-21 04:23:18', '2024-01-21 04:23:18'),
(194, 'create size', 'web', '2024-01-21 04:23:23', '2024-01-21 04:23:23'),
(195, 'read product', 'web', '2024-01-21 04:39:35', '2024-01-21 04:39:35'),
(196, 'write product', 'web', '2024-01-21 04:40:12', '2024-01-21 04:40:12'),
(197, 'create product', 'web', '2024-01-21 04:40:22', '2024-01-21 04:40:22'),
(198, 'read purchase', 'web', '2024-01-21 04:43:58', '2024-01-21 04:43:58'),
(199, 'write purchase', 'web', '2024-01-21 04:44:04', '2024-01-21 04:44:04'),
(200, 'create purchase', 'web', '2024-01-21 04:44:09', '2024-01-21 04:44:09'),
(201, 'read sales', 'web', '2024-01-21 04:44:23', '2024-01-21 04:44:23'),
(202, 'write sales', 'web', '2024-01-21 04:44:53', '2024-01-21 04:44:53'),
(203, 'create sales', 'web', '2024-01-21 04:44:58', '2024-01-21 04:44:58'),
(204, 'read purchase report', 'web', '2024-01-21 04:45:17', '2024-01-21 04:45:17'),
(205, 'write purchase report', 'web', '2024-01-21 04:45:24', '2024-01-21 04:45:24'),
(206, 'create purchase report', 'web', '2024-01-21 04:45:29', '2024-01-21 04:45:29'),
(207, 'read sales report', 'web', '2024-01-21 04:45:45', '2024-01-21 04:45:45'),
(208, 'write sales report', 'web', '2024-01-21 04:45:53', '2024-01-21 04:45:53'),
(209, 'create sales report', 'web', '2024-01-21 04:46:02', '2024-01-21 04:46:02'),
(210, 'read stock report', 'web', '2024-01-21 04:46:21', '2024-01-21 04:46:21'),
(211, 'read cost sheet', 'web', '2024-01-21 04:49:09', '2024-01-21 04:49:09'),
(212, 'write cost sheet', 'web', '2024-01-21 04:49:17', '2024-01-21 04:49:17'),
(213, 'create cost sheet', 'web', '2024-01-21 04:49:22', '2024-01-21 04:49:22'),
(214, 'read product order', 'web', '2024-01-21 05:06:02', '2024-01-21 05:06:02'),
(215, 'write product order', 'web', '2024-01-21 05:06:13', '2024-01-21 05:06:13'),
(216, 'create product order', 'web', '2024-01-21 05:06:22', '2024-01-21 05:06:22'),
(220, 'read payroll head', 'web', '2024-04-08 03:47:41', '2024-04-08 03:47:41'),
(221, 'write payroll head', 'web', '2024-04-08 03:48:13', '2024-04-08 03:48:13'),
(222, 'create payroll head', 'web', '2024-04-08 03:48:22', '2024-04-08 03:48:22'),
(223, 'read payroll formula', 'web', '2024-04-08 03:54:19', '2024-04-08 03:54:19'),
(224, 'write payroll formula', 'web', '2024-04-08 03:54:31', '2024-04-08 03:54:31'),
(225, 'create payroll formula', 'web', '2024-04-08 03:54:41', '2024-04-08 03:54:41'),
(226, 'read payroll add', 'web', '2024-04-08 22:58:11', '2024-04-08 22:58:11'),
(227, 'write payroll add', 'web', '2024-04-08 22:58:28', '2024-04-08 22:58:28'),
(228, 'create payroll add', 'web', '2024-04-08 22:58:36', '2024-04-08 22:58:36'),
(229, 'read company setting', 'web', '2024-04-08 23:19:43', '2024-04-08 23:19:43'),
(230, 'write company setting', 'web', '2024-04-08 23:19:55', '2024-04-08 23:19:55'),
(231, 'create company setting', 'web', '2024-04-08 23:20:04', '2024-04-08 23:20:04'),
(232, 'read billofmaterials', 'web', '2024-04-22 23:51:10', '2024-04-22 23:51:10'),
(233, 'write billofmaterials', 'web', '2024-04-22 23:51:18', '2024-04-22 23:51:18'),
(234, 'create billofmaterials', 'web', '2024-04-22 23:51:30', '2024-04-22 23:51:30'),
(235, 'read finalproductions', 'web', '2024-04-22 23:54:32', '2024-04-22 23:54:32'),
(236, 'write finalproductions', 'web', '2024-04-22 23:54:41', '2024-04-22 23:54:41'),
(237, 'create finalproductions', 'web', '2024-04-22 23:54:49', '2024-04-22 23:54:49'),
(238, 'read finalproduction report', 'web', '2024-04-23 00:00:49', '2024-04-23 00:00:49'),
(239, 'write finalproduction report', 'web', '2024-04-23 00:00:58', '2024-04-23 00:00:58'),
(240, 'create finalproduction report', 'web', '2024-04-23 00:01:04', '2024-04-23 00:01:04'),
(241, 'read product type', 'web', '2024-05-08 07:55:53', '2024-05-08 07:55:53'),
(242, 'write product type', 'web', '2024-05-08 07:56:01', '2024-05-08 07:56:01'),
(243, 'create product type', 'web', '2024-05-08 07:56:09', '2024-05-08 07:56:09'),
(244, 'read unit', 'web', '2024-05-08 08:03:25', '2024-05-08 08:03:25'),
(245, 'write unit', 'web', '2024-05-08 08:03:36', '2024-05-08 08:03:36'),
(246, 'create unit', 'web', '2024-05-08 08:03:43', '2024-05-08 08:03:43'),
(247, 'read product fragrance', 'web', '2024-05-08 10:08:30', '2024-05-08 10:08:30'),
(248, 'write product fragrance', 'web', '2024-05-08 10:08:38', '2024-05-08 10:08:38'),
(249, 'create product fragrance', 'web', '2024-05-08 10:08:47', '2024-05-08 10:08:47'),
(250, 'read customer type', 'web', '2024-05-14 11:38:53', '2024-05-14 11:38:53'),
(251, 'write customer type', 'web', '2024-05-14 11:39:02', '2024-05-14 11:39:02'),
(252, 'create customer type', 'web', '2024-05-14 11:39:10', '2024-05-14 11:39:10'),
(253, 'delete customer type', 'web', '2024-05-16 08:54:45', '2024-05-16 08:54:45'),
(254, 'read requisition', 'web', '2024-05-16 11:05:31', '2024-05-16 11:05:31'),
(255, 'write requisition', 'web', '2024-05-16 11:05:49', '2024-05-16 11:05:49'),
(256, 'create requisition', 'web', '2024-05-16 11:06:04', '2024-05-16 11:06:04'),
(257, 'read requisition list', 'web', '2024-05-16 11:06:37', '2024-05-16 11:06:37'),
(258, 'read requisition approved list', 'web', '2024-05-20 07:04:49', '2024-05-20 07:04:49'),
(259, 'read billofmaterials list', 'web', '2024-05-23 08:43:01', '2024-05-23 08:43:01'),
(260, 'write billofmaterials list', 'web', '2024-05-23 08:43:39', '2024-05-23 08:43:39'),
(261, 'create billofmaterials list', 'web', '2024-05-23 08:43:50', '2024-05-23 08:43:50'),
(262, 'create sales report datewise', 'web', '2024-06-12 09:57:21', '2024-06-12 09:57:21'),
(263, 'write sales report datewise', 'web', '2024-06-12 09:58:17', '2024-06-12 09:58:17'),
(264, 'read sales report datewise', 'web', '2024-06-12 09:58:41', '2024-06-12 09:58:41'),
(265, 'read finance transaction', 'web', '2024-07-04 11:07:52', '2024-07-04 11:07:52'),
(266, 'write finance transaction', 'web', '2024-07-04 11:08:31', '2024-07-04 11:08:31'),
(267, 'create finance transaction', 'web', '2024-07-04 11:15:38', '2024-07-04 11:15:38'),
(268, 'read finance account', 'web', '2024-07-04 11:24:46', '2024-07-04 11:24:46'),
(269, 'write finance account', 'web', '2024-07-04 11:25:45', '2024-07-04 11:25:45'),
(270, 'create finance account', 'web', '2024-07-04 11:25:57', '2024-07-04 11:25:57'),
(271, 'read finance group', 'web', '2024-07-04 11:27:08', '2024-07-04 11:27:08'),
(272, 'write finance group', 'web', '2024-07-04 11:27:43', '2024-07-04 11:27:43'),
(273, 'create finance group', 'web', '2024-07-04 11:27:58', '2024-07-04 11:27:58'),
(274, 'read received voucher', 'web', '2024-07-04 11:40:25', '2024-07-04 11:40:25'),
(275, 'write received voucher', 'web', '2024-07-04 11:40:43', '2024-07-04 11:40:43'),
(276, 'create received voucher', 'web', '2024-07-04 11:41:08', '2024-07-04 11:41:08'),
(277, 'read payment voucher', 'web', '2024-07-04 12:26:29', '2024-07-04 12:26:29'),
(278, 'write payment voucher', 'web', '2024-07-04 12:26:50', '2024-07-04 12:26:50'),
(279, 'create payment voucher', 'web', '2024-07-04 12:27:00', '2024-07-04 12:27:00'),
(280, 'read general ledger', 'web', '2024-07-04 12:51:27', '2024-07-04 12:51:27'),
(284, 'read order', 'web', '2024-09-02 07:11:11', '2024-09-02 07:11:11'),
(285, 'write order', 'web', '2024-09-02 07:12:48', '2024-09-02 07:12:48'),
(286, 'create order', 'web', '2024-09-02 07:12:58', '2024-09-02 07:12:58'),
(288, 'read warehouse', 'web', '2024-10-21 10:11:58', '2024-10-21 10:11:58'),
(289, 'write warehouse', 'web', '2024-10-21 10:12:05', '2024-10-21 10:12:05'),
(290, 'create warehouse', 'web', '2024-10-21 10:12:14', '2024-10-21 10:12:14'),
(291, 'read item wise profit', 'web', '2024-11-03 05:44:40', '2024-11-03 05:44:40'),
(292, 'read invoice wise profit', 'web', '2024-11-03 05:47:43', '2024-11-03 05:47:43'),
(293, 'read product service', 'web', '2024-11-04 11:22:06', '2024-11-04 11:22:06'),
(294, 'write product service', 'web', '2024-11-04 11:22:15', '2024-11-04 11:22:15'),
(295, 'create product service', 'web', '2024-11-04 11:22:22', '2024-11-04 11:22:22'),
(296, 'read pending product service', 'web', '2024-11-04 11:22:36', '2024-11-04 11:22:36'),
(297, 'read complete product service', 'web', '2024-11-04 11:22:56', '2024-11-04 11:22:56');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(18,2) NOT NULL DEFAULT 0.00,
  `unit_id` int(50) DEFAULT NULL,
  `done_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productions`
--

INSERT INTO `productions` (`id`, `product_id`, `quantity`, `unit_id`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 3, '1.00', 4, ' ', '2024-10-31 10:27:42', '2024-10-31 10:27:42'),
(2, 3, '1.00', 4, ' ', '2024-11-28 07:18:31', '2024-11-28 07:18:31'),
(3, 3, '1.00', 4, ' ', '2024-11-28 10:48:16', '2024-11-28 10:48:16'),
(4, 6, '1.00', 4, ' ', '2024-11-28 10:51:53', '2024-11-28 10:51:53');

-- --------------------------------------------------------

--
-- Table structure for table `production_bill_of_matrials`
--

CREATE TABLE `production_bill_of_matrials` (
  `id` int(11) NOT NULL,
  `production_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(18,2) NOT NULL DEFAULT 0.00,
  `product_unit` int(50) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `done_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `production_bill_of_matrials`
--

INSERT INTO `production_bill_of_matrials` (`id`, `production_id`, `product_id`, `quantity`, `product_unit`, `type`, `done_by`, `created_at`, `updated_at`) VALUES
(5, 3, 4, '1.00', 4, 1, ' ', '2024-11-28 10:48:16', '2024-11-28 10:48:16'),
(6, 3, 5, '1.00', 4, 1, ' ', '2024-11-28 10:48:16', '2024-11-28 10:48:16'),
(8, 4, 5, '2.00', 4, 1, ' ', '2024-11-28 10:51:53', '2024-11-28 10:51:53'),
(9, 4, 4, '1.00', 4, 1, ' ', '2024-11-28 10:51:53', '2024-11-28 10:51:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `type_id` int(20) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `sub_category_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `color_id` int(10) UNSIGNED DEFAULT NULL,
  `size_id` int(10) UNSIGNED DEFAULT NULL,
  `unit_id` int(10) UNSIGNED DEFAULT NULL,
  `fragrance_id` int(11) DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `hs_code` varchar(255) DEFAULT NULL,
  `article_no` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_slug` varchar(255) DEFAULT NULL,
  `country_Of_origin` varchar(255) DEFAULT NULL,
  `product_thickness` varchar(255) DEFAULT NULL,
  `pack_size` decimal(18,2) NOT NULL DEFAULT 0.00,
  `purchase_price` decimal(30,3) DEFAULT 0.000,
  `profit_percent` decimal(18,2) DEFAULT NULL,
  `wholesale_price` decimal(30,3) DEFAULT 0.000,
  `sales_price` decimal(30,3) DEFAULT 0.000,
  `credit_sales_price` decimal(30,3) DEFAULT 0.000,
  `minimum_alert_quantity` decimal(18,2) DEFAULT NULL,
  `is_vatable` int(11) DEFAULT NULL,
  `vat_rate` decimal(18,2) DEFAULT NULL,
  `is_taxable` int(11) DEFAULT NULL,
  `tax_rate` decimal(18,2) DEFAULT NULL,
  `product_image1` varchar(255) DEFAULT NULL,
  `product_image2` varchar(255) DEFAULT NULL,
  `product_description` longtext DEFAULT NULL,
  `product_remarks` longtext DEFAULT NULL,
  `order` varchar(255) DEFAULT '0',
  `is_purchaseable` int(11) NOT NULL,
  `is_saleable` int(11) NOT NULL,
  `is_produceable` int(11) NOT NULL,
  `is_consumable` int(11) NOT NULL,
  `is_serviceable` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `supplier_id`, `type_id`, `category_id`, `sub_category_id`, `brand_id`, `color_id`, `size_id`, `unit_id`, `fragrance_id`, `product_code`, `hs_code`, `article_no`, `product_name`, `product_slug`, `country_Of_origin`, `product_thickness`, `pack_size`, `purchase_price`, `profit_percent`, `wholesale_price`, `sales_price`, `credit_sales_price`, `minimum_alert_quantity`, `is_vatable`, `vat_rate`, `is_taxable`, `tax_rate`, `product_image1`, `product_image2`, `product_description`, `product_remarks`, `order`, `is_purchaseable`, `is_saleable`, `is_produceable`, `is_consumable`, `is_serviceable`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 1, NULL, NULL, NULL, NULL, 4, NULL, '01', NULL, NULL, 'Manola Men Perfume', NULL, NULL, NULL, '0.00', '1600.000', NULL, '0.000', '2000.000', '0.000', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 0, 0, 1, 1, '2024-10-16 11:41:07', '2024-10-16 11:41:07'),
(2, NULL, 2, 1, NULL, NULL, NULL, NULL, 4, NULL, '02', NULL, NULL, 'Manola Perfume Oil', NULL, NULL, NULL, '0.00', '250.000', NULL, '0.000', '300.000', '0.000', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 0, 0, 1, 1, '2024-10-16 11:42:21', '2024-10-16 11:42:21'),
(3, NULL, 1, 3, NULL, 1, 1, 1, 4, NULL, 'P01', '65656', NULL, 'Manola Body Lotion', NULL, 'Bangladesh', NULL, '1.00', '700.000', NULL, '0.000', '1000.000', '0.000', '100.00', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 1, 0, 1, 1, '2024-10-31 09:48:42', '2024-10-31 09:49:47'),
(4, NULL, 1, 3, NULL, 1, NULL, NULL, 4, NULL, 'P02', '65656', NULL, 'Manola lip gel', NULL, 'Bangladesh', NULL, '0.00', '40.000', NULL, '0.000', '50.000', '0.000', '12.00', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 1, 1, 1, 1, '2024-10-31 09:51:44', '2024-10-31 09:51:44'),
(5, NULL, 1, 2, 1, 1, NULL, NULL, 4, NULL, 'P03', '65656', NULL, 'Manola Hair Oil', NULL, 'Bangladesh', NULL, '0.00', '480.000', NULL, '0.000', '590.000', '0.000', '10.00', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 1, 1, 1, 1, '2024-10-31 09:54:34', '2024-10-31 09:54:34'),
(6, NULL, 1, 2, 2, 1, NULL, NULL, 4, NULL, 'P0004', NULL, NULL, 'Manola Shampoo', NULL, NULL, NULL, '0.00', '390.000', NULL, '0.000', '480.000', '0.000', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 1, 1, 1, 1, '2024-10-31 09:56:06', '2024-10-31 09:56:06'),
(7, NULL, 1, 4, NULL, 2, 1, NULL, 1, NULL, 'P0005', NULL, NULL, 'A4 Paper-70GSM', NULL, 'Bangladesh', NULL, '0.00', '184.000', NULL, '0.000', '152.730', '0.000', '1000.00', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 1, 1, 1, 1, '2024-10-31 10:02:20', '2024-10-31 10:02:20'),
(8, NULL, 1, 4, NULL, 2, 1, 3, 1, NULL, 'P0006', '65656', NULL, 'A3 Paper 68GSM', NULL, 'Bangladesh', NULL, '0.00', '147.000', NULL, '0.000', '500.000', '0.000', '1000.00', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 1, 1, 1, 1, '2024-10-31 10:03:39', '2024-10-31 10:03:39'),
(9, NULL, 1, 1, NULL, 1, 4, 1, 2, NULL, 'P0008', NULL, NULL, 'Manola Mens Body Spray', NULL, NULL, NULL, '0.00', '700.000', NULL, '0.000', '1000.000', '0.000', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 1, 1, 1, 1, '2024-10-31 10:05:50', '2024-10-31 10:05:50'),
(10, NULL, 1, 1, NULL, 1, 3, 1, 1, NULL, 'P0009', '65656', NULL, 'Manola Girls Body Perfume', NULL, 'Bangladesh', NULL, '0.00', '700.000', NULL, '0.000', '1000.000', '0.000', '100.00', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 1, 1, 1, 1, '2024-10-31 10:08:22', '2024-10-31 10:08:22'),
(11, NULL, 1, 1, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 'Manola Winter Perfume', NULL, NULL, NULL, '0.00', '1500.000', NULL, '0.000', '2000.000', '0.000', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, 0, 0, 1, 1, '2024-10-31 10:17:41', '2024-10-31 10:17:41'),
(12, NULL, 3, 6, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'Conveyance', NULL, NULL, NULL, '0.00', '0.000', NULL, '0.000', '5000.000', '0.000', NULL, NULL, NULL, 1, '0.00', NULL, NULL, NULL, NULL, '0', 0, 1, 0, 0, 0, 1, '2024-11-17 09:14:54', '2024-11-17 09:14:54'),
(13, NULL, 3, 6, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'Outside Product', NULL, NULL, NULL, '5.00', '0.000', NULL, '0.000', '2000.000', '0.000', NULL, NULL, NULL, 1, '0.00', NULL, NULL, NULL, NULL, '0', 0, 1, 0, 0, 0, 1, '2024-11-17 09:15:51', '2024-11-18 08:03:07');

-- --------------------------------------------------------

--
-- Table structure for table `product_brands`
--

CREATE TABLE `product_brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `product_brands`
--

INSERT INTO `product_brands` (`id`, `brand_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Manola', 1, '2024-09-22 09:49:43', '2024-10-16 09:58:10'),
(2, 'Sonali', 1, '2024-10-31 09:39:10', '2024-10-31 09:39:10');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `order` varchar(255) DEFAULT '0',
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `category_name`, `slug`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Perfume', NULL, '0', 1, '2024-09-22 09:49:06', '2024-10-16 09:58:52'),
(2, 'Hair Care', NULL, '0', 1, '2024-09-22 09:49:28', '2024-10-16 09:59:25'),
(3, 'Moisturizer', NULL, '0', 1, '2024-09-24 20:15:01', '2024-10-16 10:00:38'),
(4, 'Paper', NULL, '0', 1, '2024-10-31 09:40:06', '2024-10-31 09:40:06'),
(5, 'Tissue', NULL, '0', 1, '2024-10-31 09:40:30', '2024-10-31 09:40:30'),
(6, 'Product Service', NULL, '0', 1, '2024-11-17 09:11:45', '2024-11-17 09:11:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_colors`
--

CREATE TABLE `product_colors` (
  `id` int(10) UNSIGNED NOT NULL,
  `color_name` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `product_colors`
--

INSERT INTO `product_colors` (`id`, `color_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'White', 1, '2024-04-27 00:39:34', '2024-04-27 00:39:34'),
(2, 'Golden', 1, '2024-04-27 00:41:10', '2024-04-27 00:41:10'),
(3, 'Pink', 1, '2024-04-27 00:41:16', '2024-04-27 00:41:16'),
(4, 'Black', 1, '2024-05-23 07:49:10', '2024-05-23 07:49:24'),
(5, 'Red', 1, '2024-06-10 12:17:34', '2024-06-10 12:17:34'),
(6, 'Yellow', 1, '2024-06-10 12:17:52', '2024-06-10 12:18:42'),
(7, 'Gray', 1, '2024-06-10 12:18:17', '2024-06-10 12:18:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_fragrances`
--

CREATE TABLE `product_fragrances` (
  `id` int(10) NOT NULL,
  `fragrance_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_fragrances`
--

INSERT INTO `product_fragrances` (`id`, `fragrance_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Flower', 1, '2024-11-03 06:19:50', '2024-11-03 06:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `product_orders`
--

CREATE TABLE `product_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `order_category` varchar(255) DEFAULT NULL,
  `order_type` varchar(255) DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `manufacturer_article_no` varchar(255) DEFAULT NULL,
  `customer_article_no` varchar(255) DEFAULT NULL,
  `last_no` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT NULL,
  `delivery_date` timestamp NULL DEFAULT NULL,
  `status` int(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_order_details`
--

CREATE TABLE `product_order_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_order_id` int(10) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_order_details_chains`
--

CREATE TABLE `product_order_details_chains` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_order_details_id` int(10) UNSIGNED DEFAULT NULL,
  `size_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` int(10) DEFAULT 0,
  `unit_price` decimal(18,2) DEFAULT 0.00,
  `total_price` decimal(18,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_services`
--

CREATE TABLE `product_services` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `service_start_date` date DEFAULT NULL,
  `service_end_date` date DEFAULT NULL,
  `service_quantity` int(11) DEFAULT NULL,
  `service_description` text DEFAULT NULL,
  `service_location` text DEFAULT NULL,
  `done_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_services`
--

INSERT INTO `product_services` (`id`, `invoice_no`, `customer_id`, `product_id`, `service_type`, `service_start_date`, `service_end_date`, `service_quantity`, `service_description`, `service_location`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 'INV000052', 208, 10, '1', '2024-11-01', '2024-12-31', 1, NULL, 'Gulshan, Dhaka, Bangladesh', 'Demo', '2024-11-27 10:44:06', '2024-11-27 10:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `product_service_details`
--

CREATE TABLE `product_service_details` (
  `id` int(11) NOT NULL,
  `product_service_id` int(11) NOT NULL,
  `service_invoice` varchar(255) DEFAULT NULL,
  `service_date` date NOT NULL,
  `actual_service_date` date DEFAULT NULL,
  `service_number` int(11) NOT NULL,
  `service_man_name` varchar(100) DEFAULT NULL,
  `service_man_mobile` varchar(20) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `service_status` int(11) DEFAULT 0,
  `done_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_service_details`
--

INSERT INTO `product_service_details` (`id`, `product_service_id`, `service_invoice`, `service_date`, `actual_service_date`, `service_number`, `service_man_name`, `service_man_mobile`, `remarks`, `service_status`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'INV000054', '2024-11-27', '2024-11-28', 1, 'Masum', '01681952638', 'Remarks .....', 1, 'Demo', '2024-11-27 10:44:06', '2024-11-28 07:04:55');

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(10) UNSIGNED NOT NULL,
  `size_name` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `size_name`, `status`, `created_at`, `updated_at`) VALUES
(1, '700ml', 1, '2024-10-31 09:43:36', '2024-10-31 09:43:36'),
(2, 'A4', 1, '2024-10-31 09:58:18', '2024-10-31 09:58:18'),
(3, 'A3', 1, '2024-10-31 09:58:29', '2024-10-31 09:58:29');

-- --------------------------------------------------------

--
-- Table structure for table `product_sub_categories`
--

CREATE TABLE `product_sub_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `sub_category_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `order` varchar(255) DEFAULT '0',
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `product_sub_categories`
--

INSERT INTO `product_sub_categories` (`id`, `category_id`, `sub_category_name`, `slug`, `order`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Oil', NULL, '0', 1, '2024-10-31 09:52:33', '2024-10-31 09:52:33'),
(2, 2, 'shampoo', NULL, '0', 1, '2024-10-31 09:52:53', '2024-10-31 09:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` int(10) NOT NULL,
  `type_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `type_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Finish Goods', 1, '2024-09-22 09:48:19', '2024-09-22 09:48:19'),
(2, 'Raw Materials', 1, '2024-09-22 09:48:31', '2024-09-22 09:48:31'),
(3, 'Sevice Item', 1, '2024-11-17 09:11:14', '2024-11-17 09:11:14');

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

CREATE TABLE `product_units` (
  `id` int(10) UNSIGNED NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `unit_value` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `product_units`
--

INSERT INTO `product_units` (`id`, `unit_name`, `unit_value`, `status`, `created_at`, `updated_at`) VALUES
(1, 'kg', '1', 1, '2024-09-22 09:50:06', '2024-09-22 09:50:06'),
(2, 'gm', '1', 1, '2024-09-22 09:50:41', '2024-09-22 09:50:41'),
(3, 'cm', '1', 1, '2024-09-22 09:50:52', '2024-09-22 09:50:52'),
(4, 'pcs', '1', 1, '2024-09-24 20:15:25', '2024-10-16 09:57:57'),
(5, 'One time', '1', 1, '2024-11-17 09:13:11', '2024-11-17 09:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL DEFAULT 0,
  `branch_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `designation_id` int(11) NOT NULL DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `employee_id`, `branch_id`, `department_id`, `designation_id`, `start_date`, `end_date`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 4, 1, '2024-01-01', NULL, '', 1, '2024-01-21 03:21:27', '2024-01-21 03:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `id` int(10) UNSIGNED NOT NULL,
  `stock_date` datetime DEFAULT NULL,
  `stock_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `invoice_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `purchase_price` decimal(18,2) DEFAULT 0.00,
  `stock_in_quantity` decimal(18,2) DEFAULT 0.00,
  `stock_in_unit_price` decimal(18,2) DEFAULT 0.00,
  `stock_in_discount` decimal(18,2) DEFAULT 0.00,
  `stock_in_total_amount` decimal(18,2) DEFAULT 0.00,
  `stock_out_quantity` decimal(18,2) DEFAULT 0.00,
  `stock_out_unit_price` decimal(18,2) DEFAULT 0.00,
  `stock_out_discount` decimal(18,2) DEFAULT 0.00,
  `stock_out_total_amount` decimal(18,2) DEFAULT 0.00,
  `done_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `approved_level` int(11) DEFAULT 3,
  `read_at` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requisitions`
--

INSERT INTO `requisitions` (`id`, `stock_date`, `stock_type`, `invoice_no`, `product_id`, `purchase_price`, `stock_in_quantity`, `stock_in_unit_price`, `stock_in_discount`, `stock_in_total_amount`, `stock_out_quantity`, `stock_out_unit_price`, `stock_out_discount`, `stock_out_total_amount`, `done_by`, `remarks`, `status`, `approved_level`, `read_at`, `created_at`, `updated_at`) VALUES
(1, '2024-10-31 16:41:07', 'ProductionIn', 'REQ000001', 3, '0.00', '5.00', '200.00', '0.00', '200.00', '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, 3, 0, '2024-10-31 10:41:07', '2024-10-31 10:41:07'),
(2, '2024-10-31 16:41:07', 'ConsumeOut', 'REQ000001', 4, '0.00', '0.00', '0.00', '0.00', '0.00', '25.00', '40.00', '0.00', '1000.00', 'Demo', NULL, 0, 3, 0, '2024-10-31 10:41:07', '2024-10-31 10:41:07'),
(3, '2024-10-31 16:41:07', 'ConsumeOut', 'REQ000001', 5, '0.00', '0.00', '0.00', '0.00', '0.00', '1.00', '480.00', '0.00', '480.00', 'Demo', NULL, 0, 3, 0, '2024-10-31 10:41:07', '2024-10-31 10:41:07'),
(4, '2024-11-28 13:30:56', 'ProductionIn', 'REQ000002', 3, '0.00', '2000.00', '200.00', '0.00', '200.00', '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, 3, 0, '2024-11-28 07:30:56', '2024-11-28 07:30:56'),
(5, '2024-11-28 13:30:56', 'ConsumeOut', 'REQ000002', 4, '0.00', '0.00', '0.00', '0.00', '0.00', '10000.00', '40.00', '0.00', '400000.00', 'Demo', NULL, 0, 3, 0, '2024-11-28 07:30:56', '2024-11-28 07:30:56'),
(6, '2024-11-28 13:30:56', 'ConsumeOut', 'REQ000002', 5, '0.00', '0.00', '0.00', '0.00', '0.00', '50.00', '480.00', '0.00', '24000.00', 'Demo', NULL, 0, 3, 0, '2024-11-28 07:30:56', '2024-11-28 07:30:56'),
(7, '2024-11-28 16:52:44', 'ProductionIn', 'REQ000003', 6, '0.00', '5.00', '1000.00', '0.00', '1000.00', '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, 3, 0, '2024-11-28 10:52:44', '2024-11-28 10:52:44'),
(8, '2024-11-28 16:52:44', 'ConsumeOut', 'REQ000003', 5, '0.00', '0.00', '0.00', '0.00', '0.00', '10.00', '480.00', '0.00', '4800.00', 'Demo', NULL, 0, 3, 0, '2024-11-28 10:52:44', '2024-11-28 10:52:44'),
(9, '2024-11-28 16:52:44', 'ConsumeOut', 'REQ000003', 4, '0.00', '0.00', '0.00', '0.00', '0.00', '5.00', '40.00', '0.00', '200.00', 'Demo', NULL, 0, 3, 0, '2024-11-28 10:52:44', '2024-11-28 10:52:44'),
(10, '2024-11-28 16:52:44', 'ConsumeOut', 'REQ000003', 7, '0.00', '0.00', '0.00', '0.00', '0.00', '1.00', '184.00', '0.00', '184.00', 'Demo', NULL, 0, 3, 0, '2024-11-28 10:52:44', '2024-11-28 10:52:44');

-- --------------------------------------------------------

--
-- Table structure for table `resignations`
--

CREATE TABLE `resignations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL DEFAULT 0,
  `notice_date` date NOT NULL,
  `resignation_date` date NOT NULL,
  `last_working_date` date NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resignations`
--

INSERT INTO `resignations` (`id`, `employee_id`, `notice_date`, `resignation_date`, `last_working_date`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-09-04', '2023-10-01', '2023-09-30', 'dgdfgfdgfdgf', 1, '2023-09-03 23:55:24', '2023-09-03 23:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(18,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`id`, `title`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Entry Ticket', '<p>Entry Ticket Description</p>', '140.00', 'images/ride/15-07-2025-18-16-49-wonder-park-entry-gate.jpg', 1, '2025-07-15 12:16:49', '2025-07-16 08:02:02'),
(4, 'Pirate ship', '<p>Pirate ship Description</p>', '100.00', 'images/ride/16-07-2025-12-10-40-Pirate-ship-2.jpg', 1, '2025-07-16 06:10:40', '2025-07-21 09:36:35'),
(5, 'Mickey Train', '<p>Mickey Train Description</p>', '100.00', 'images/ride/16-07-2025-12-11-08-Mickey-Train.jpg', 1, '2025-07-16 06:11:08', '2025-07-21 09:36:59'),
(6, 'Swing chair', 'Swing chair for 16 persons of all ages', '50.00', 'images/ride/23-07-2025-13-06-24-Swing-chair.jpg', 1, '2025-07-23 07:06:24', '2025-07-23 07:06:24');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'web', '2023-07-25 04:29:52', '2023-08-01 02:53:54'),
(2, 'developer', 'web', '2023-07-25 04:29:54', '2023-07-25 04:29:54'),
(3, 'employee', 'web', '2023-07-25 04:50:46', '2023-07-25 04:50:46'),
(7, 'HR', 'web', '2023-09-20 01:07:53', '2023-11-07 03:38:01'),
(8, 'Chemist', 'web', '2024-05-14 11:45:21', '2024-05-14 11:45:21'),
(9, 'Production Manager', 'web', '2024-05-14 11:45:58', '2024-05-14 11:45:58'),
(10, 'Store Incharge', 'web', '2024-05-14 11:46:30', '2024-05-14 11:46:54'),
(11, 'CEO/MD', 'web', '2024-05-16 08:34:36', '2024-05-16 08:34:36'),
(12, 'Admin', 'web', '2024-09-22 10:11:30', '2024-09-22 10:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(40, 1),
(40, 2),
(41, 1),
(41, 2),
(42, 1),
(42, 2),
(49, 1),
(49, 2),
(49, 7),
(50, 1),
(50, 2),
(50, 7),
(51, 1),
(51, 2),
(51, 7),
(52, 1),
(52, 2),
(52, 7),
(53, 1),
(53, 2),
(53, 7),
(54, 1),
(54, 2),
(54, 7),
(55, 1),
(55, 2),
(55, 3),
(55, 7),
(56, 1),
(56, 2),
(56, 3),
(56, 7),
(57, 1),
(57, 2),
(57, 3),
(57, 7),
(73, 1),
(73, 2),
(73, 7),
(74, 1),
(74, 2),
(74, 7),
(80, 1),
(80, 2),
(80, 7),
(81, 1),
(81, 2),
(81, 7),
(82, 1),
(82, 2),
(82, 3),
(82, 7),
(83, 1),
(83, 2),
(83, 3),
(83, 7),
(86, 1),
(86, 2),
(86, 7),
(87, 1),
(87, 2),
(87, 7),
(90, 1),
(90, 2),
(91, 1),
(91, 2),
(93, 1),
(93, 2),
(94, 1),
(94, 2),
(95, 1),
(95, 2),
(95, 7),
(96, 1),
(96, 2),
(96, 7),
(97, 1),
(97, 2),
(97, 7),
(99, 1),
(99, 2),
(100, 1),
(100, 2),
(101, 1),
(101, 2),
(102, 1),
(102, 2),
(103, 1),
(103, 2),
(104, 1),
(104, 2),
(109, 1),
(109, 2),
(110, 1),
(110, 2),
(111, 1),
(111, 2),
(112, 1),
(112, 2),
(113, 1),
(113, 2),
(114, 1),
(114, 2),
(115, 1),
(115, 2),
(116, 1),
(116, 2),
(117, 1),
(117, 2),
(118, 1),
(118, 2),
(119, 1),
(119, 2),
(120, 1),
(120, 2),
(121, 1),
(121, 2),
(122, 1),
(122, 2),
(123, 1),
(123, 2),
(124, 1),
(124, 2),
(125, 1),
(125, 2),
(126, 1),
(126, 2),
(127, 1),
(127, 2),
(128, 1),
(128, 2),
(129, 1),
(129, 2),
(131, 1),
(131, 2),
(132, 1),
(132, 2),
(133, 1),
(133, 2),
(134, 1),
(134, 2),
(134, 3),
(135, 1),
(135, 2),
(135, 3),
(136, 1),
(136, 2),
(137, 1),
(137, 2),
(137, 7),
(138, 1),
(138, 2),
(138, 7),
(139, 1),
(139, 2),
(139, 7),
(140, 1),
(140, 2),
(141, 1),
(141, 2),
(142, 1),
(142, 2),
(143, 1),
(143, 2),
(144, 1),
(144, 2),
(145, 1),
(145, 2),
(153, 1),
(153, 3),
(153, 7),
(154, 1),
(154, 7),
(155, 1),
(155, 7),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(162, 7),
(163, 1),
(163, 7),
(164, 1),
(164, 7),
(165, 1),
(165, 7),
(166, 1),
(166, 7),
(167, 1),
(167, 7),
(168, 1),
(168, 7),
(168, 12),
(169, 1),
(169, 7),
(169, 12),
(170, 1),
(170, 7),
(170, 12),
(171, 1),
(171, 7),
(172, 1),
(172, 7),
(173, 1),
(173, 7),
(174, 1),
(174, 7),
(174, 12),
(175, 1),
(175, 7),
(175, 12),
(176, 1),
(176, 7),
(176, 12),
(177, 1),
(177, 7),
(178, 1),
(178, 7),
(179, 1),
(179, 7),
(180, 1),
(180, 7),
(180, 10),
(180, 12),
(181, 1),
(181, 7),
(181, 10),
(181, 12),
(182, 1),
(182, 7),
(182, 10),
(182, 12),
(183, 1),
(183, 7),
(183, 10),
(183, 12),
(184, 1),
(184, 7),
(184, 10),
(184, 12),
(185, 1),
(185, 7),
(185, 10),
(185, 12),
(186, 1),
(186, 7),
(186, 10),
(186, 12),
(187, 1),
(187, 7),
(187, 10),
(187, 12),
(188, 1),
(188, 7),
(188, 10),
(188, 12),
(189, 1),
(189, 7),
(189, 10),
(189, 12),
(190, 1),
(190, 7),
(190, 10),
(190, 12),
(191, 1),
(191, 7),
(191, 10),
(191, 12),
(192, 1),
(192, 7),
(192, 10),
(192, 12),
(193, 1),
(193, 7),
(193, 10),
(193, 12),
(194, 1),
(194, 7),
(194, 10),
(194, 12),
(195, 1),
(195, 7),
(195, 10),
(195, 12),
(196, 1),
(196, 7),
(196, 10),
(196, 12),
(197, 1),
(197, 7),
(197, 10),
(197, 12),
(198, 1),
(198, 7),
(198, 10),
(198, 12),
(199, 1),
(199, 7),
(199, 10),
(199, 12),
(200, 1),
(200, 7),
(200, 10),
(200, 12),
(201, 1),
(201, 7),
(201, 10),
(201, 12),
(202, 1),
(202, 7),
(202, 10),
(202, 12),
(203, 1),
(203, 7),
(203, 10),
(203, 12),
(204, 1),
(204, 7),
(204, 10),
(204, 12),
(205, 1),
(205, 7),
(205, 10),
(205, 12),
(206, 1),
(206, 7),
(206, 10),
(206, 12),
(207, 1),
(207, 7),
(207, 10),
(207, 12),
(208, 1),
(208, 7),
(208, 10),
(208, 12),
(209, 1),
(209, 7),
(209, 10),
(209, 12),
(210, 1),
(210, 7),
(210, 8),
(210, 10),
(210, 12),
(211, 1),
(211, 7),
(212, 1),
(212, 7),
(213, 1),
(213, 7),
(214, 1),
(214, 7),
(214, 12),
(215, 1),
(215, 7),
(215, 12),
(216, 1),
(216, 7),
(216, 12),
(220, 1),
(220, 7),
(221, 1),
(221, 7),
(222, 1),
(222, 7),
(223, 1),
(223, 7),
(224, 1),
(224, 7),
(225, 1),
(225, 7),
(226, 1),
(226, 7),
(227, 1),
(227, 7),
(228, 1),
(228, 7),
(229, 1),
(229, 7),
(229, 12),
(230, 1),
(230, 7),
(230, 12),
(231, 1),
(231, 7),
(231, 12),
(232, 1),
(232, 8),
(232, 12),
(233, 1),
(233, 8),
(233, 12),
(234, 1),
(234, 8),
(234, 12),
(235, 1),
(235, 12),
(236, 1),
(236, 12),
(237, 1),
(237, 12),
(238, 1),
(238, 8),
(238, 12),
(239, 1),
(239, 8),
(239, 12),
(240, 1),
(240, 8),
(240, 12),
(241, 1),
(241, 10),
(241, 12),
(242, 1),
(242, 10),
(242, 12),
(243, 1),
(243, 10),
(243, 12),
(244, 1),
(244, 10),
(244, 12),
(245, 1),
(245, 10),
(245, 12),
(246, 1),
(246, 10),
(246, 12),
(247, 1),
(247, 10),
(247, 12),
(248, 1),
(248, 10),
(248, 12),
(249, 1),
(249, 10),
(249, 12),
(250, 1),
(250, 12),
(251, 1),
(251, 12),
(252, 1),
(252, 12),
(253, 1),
(253, 12),
(254, 1),
(254, 8),
(254, 11),
(254, 12),
(255, 1),
(255, 8),
(255, 12),
(256, 1),
(256, 8),
(256, 12),
(257, 1),
(257, 8),
(257, 9),
(257, 10),
(257, 11),
(257, 12),
(258, 1),
(258, 8),
(258, 9),
(258, 10),
(258, 11),
(258, 12),
(259, 1),
(259, 12),
(260, 1),
(260, 12),
(261, 1),
(261, 12),
(262, 1),
(262, 11),
(262, 12),
(263, 1),
(263, 11),
(263, 12),
(264, 1),
(264, 11),
(264, 12),
(265, 1),
(265, 12),
(266, 1),
(266, 12),
(267, 1),
(267, 12),
(268, 1),
(268, 12),
(269, 1),
(269, 12),
(270, 1),
(270, 12),
(271, 1),
(271, 12),
(272, 1),
(272, 12),
(273, 1),
(273, 12),
(274, 1),
(274, 12),
(275, 1),
(275, 12),
(276, 1),
(276, 12),
(277, 1),
(277, 12),
(278, 1),
(278, 12),
(279, 1),
(279, 12),
(280, 1),
(280, 12),
(284, 1),
(284, 12),
(285, 1),
(285, 12),
(286, 1),
(286, 12),
(288, 1),
(288, 12),
(289, 1),
(289, 12),
(290, 1),
(290, 12),
(291, 1),
(291, 12),
(292, 1),
(292, 12),
(293, 1),
(293, 12),
(294, 1),
(294, 12),
(295, 1),
(295, 12),
(296, 1),
(296, 12),
(297, 1),
(297, 12);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_order` int(11) DEFAULT NULL,
  `roomtype_id` int(11) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `room_name` varchar(100) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_order`, `roomtype_id`, `room_number`, `room_name`, `floor`, `capacity`, `price_per_night`, `description`, `thumbnail_image`, `status`, `created_at`, `updated_at`) VALUES
(2, 3, 2, '0002', 'সুগন্ধা', 1, 3, '4000.00', 'BB Description', 'images/room/30-07-2025-17-10-45-image-2.jpg', 1, '2025-07-22 06:04:47', '2025-08-28 07:54:07'),
(3, 1, 3, '0003', 'চামেলি', 1, 4, '5000.00', 'CC  Description', 'images/room/30-07-2025-17-19-36-image-23.jpg', 1, '2025-07-22 06:05:34', '2025-08-28 07:36:14'),
(4, 2, 1, '0004', 'বেলি', 1, 2, '5000.00', 'AA  Description 2', 'images/room/30-07-2025-17-20-23-image-9.jpg', 1, '2025-07-28 05:45:42', '2025-08-28 07:53:58');

-- --------------------------------------------------------

--
-- Table structure for table `room_details`
--

CREATE TABLE `room_details` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `room_details`
--

INSERT INTO `room_details` (`id`, `room_id`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'images/room/30-07-2025-17-17-48-image-3.jpg', 1, '2025-07-30 11:17:48', '2025-07-30 11:17:48'),
(2, 2, 'images/room/30-07-2025-17-17-49-image-4.jpg', 1, '2025-07-30 11:17:49', '2025-07-30 11:17:49'),
(3, 2, 'images/room/30-07-2025-17-17-49-image-5.jpg', 1, '2025-07-30 11:17:49', '2025-07-30 11:17:49'),
(4, 3, 'images/room/30-07-2025-17-19-36-image-24.jpg', 1, '2025-07-30 11:19:36', '2025-07-30 11:19:36'),
(5, 3, 'images/room/30-07-2025-17-19-36-image-25.jpg', 1, '2025-07-30 11:19:36', '2025-07-30 11:19:36'),
(6, 3, 'images/room/30-07-2025-17-19-36-image-26.jpg', 1, '2025-07-30 11:19:36', '2025-07-30 11:19:36'),
(7, 4, 'images/room/30-07-2025-17-20-23-image-3.jpg', 1, '2025-07-30 11:20:23', '2025-07-30 11:20:23'),
(8, 4, 'images/room/30-07-2025-17-20-23-image-4.jpg', 1, '2025-07-30 11:20:23', '2025-07-30 11:20:23'),
(9, 4, 'images/room/30-07-2025-17-20-23-image-5.jpg', 1, '2025-07-30 11:20:23', '2025-07-30 11:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `type_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'AA', 'AA  Description', 1, '2025-07-22 11:59:00', '2025-07-24 12:29:37'),
(2, 'BB', 'BB Description', 1, '2025-07-22 11:59:16', '2025-07-22 11:59:16'),
(3, 'CC', 'CC  Description', 1, '2025-07-22 12:00:08', '2025-07-22 12:00:08'),
(4, 'DD', 'DD Description', 1, '2025-07-22 15:28:27', '2025-07-22 15:28:27');

-- --------------------------------------------------------

--
-- Table structure for table `salary_sections`
--

CREATE TABLE `salary_sections` (
  `id` int(11) NOT NULL DEFAULT 0,
  `type_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `type_name_bangla` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_description` text DEFAULT NULL,
  `service_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `set_salaries`
--

CREATE TABLE `set_salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `slider_id` int(11) DEFAULT NULL,
  `slider_image` varchar(255) DEFAULT NULL,
  `updated_at` varchar(50) NOT NULL,
  `created_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `slider_id`, `slider_image`, `updated_at`, `created_at`) VALUES
(18, NULL, 'images/sliders/1752390512_68735b70149c1.jpg', '2025-07-13 13:08:32', '2025-07-07 12:47:44'),
(19, NULL, 'images/sliders/1752390546_68735b9211e73.jpg', '2025-07-13 13:09:06', '2025-07-07 12:48:21'),
(20, NULL, 'images/sliders/1752390552_68735b98aa58f.jpg', '2025-07-13 13:09:12', '2025-07-07 12:49:00');

-- --------------------------------------------------------

--
-- Table structure for table `smslogs`
--

CREATE TABLE `smslogs` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `status` enum('Sent','Failed') DEFAULT 'Sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spots`
--

CREATE TABLE `spots` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(18,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `spots`
--

INSERT INTO `spots` (`id`, `title`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(6, 'SUGONDHA', '<p>SUGONDHA Description</p>', '5000.00', 'images/spot/14-08-2025-13-27-28-164235.JPG', 1, '2025-08-13 12:22:53', '2025-08-14 07:27:28'),
(7, 'Dhokhina', 'Convention hall\r\nPlayground\r\nDolphin Fountain\r\nStrawberry Seating Area\r\nLove Dining\r\n1 AC room', '100000.00', 'images/spot/14-08-2025-16-53-17-950913.jpg', 1, '2025-08-14 10:53:17', '2025-08-14 10:53:17'),
(8, 'SORNOLOTA', 'Capacity 100-150 Person', '50000.00', 'images/spot/14-08-2025-16-55-38-420225.jpg', 1, '2025-08-14 10:55:38', '2025-08-14 10:55:38');

-- --------------------------------------------------------

--
-- Table structure for table `spot_details`
--

CREATE TABLE `spot_details` (
  `id` int(11) NOT NULL,
  `spot_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `spot_details`
--

INSERT INTO `spot_details` (`id`, `spot_id`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(28, 6, 'images/spot/13-08-2025-18-22-53-614592.JPG', 1, '2025-08-13 12:22:54', '2025-08-13 12:22:54'),
(29, 6, 'images/spot/13-08-2025-18-22-54-984446.JPG', 1, '2025-08-13 12:22:54', '2025-08-13 12:22:54'),
(30, 6, 'images/spot/13-08-2025-18-22-54-421892.JPG', 1, '2025-08-13 12:22:54', '2025-08-13 12:22:54'),
(31, 6, 'images/spot/13-08-2025-18-22-54-652550.JPG', 1, '2025-08-13 12:22:54', '2025-08-13 12:22:54'),
(32, 6, 'images/spot/13-08-2025-18-22-54-944394.JPG', 1, '2025-08-13 12:22:54', '2025-08-13 12:22:54'),
(33, 7, 'images/spot/14-08-2025-16-53-17-826680.jpg', 1, '2025-08-14 10:53:17', '2025-08-14 10:53:17'),
(34, 7, 'images/spot/14-08-2025-16-53-17-893003.jpg', 1, '2025-08-14 10:53:17', '2025-08-14 10:53:17'),
(35, 7, 'images/spot/14-08-2025-16-53-17-167514.jpg', 1, '2025-08-14 10:53:17', '2025-08-14 10:53:17'),
(36, 7, 'images/spot/14-08-2025-16-53-17-625440.jpg', 1, '2025-08-14 10:53:17', '2025-08-14 10:53:17'),
(37, 7, 'images/spot/14-08-2025-16-53-17-680746.jpg', 1, '2025-08-14 10:53:17', '2025-08-14 10:53:17'),
(38, 7, 'images/spot/14-08-2025-16-53-17-932667.jpg', 1, '2025-08-14 10:53:17', '2025-08-14 10:53:17'),
(39, 8, 'images/spot/14-08-2025-16-55-38-869676.JPG', 1, '2025-08-14 10:55:38', '2025-08-14 10:55:38'),
(40, 8, 'images/spot/14-08-2025-16-55-38-173037.JPG', 1, '2025-08-14 10:55:38', '2025-08-14 10:55:38'),
(41, 8, 'images/spot/14-08-2025-16-55-38-791443.JPG', 1, '2025-08-14 10:55:38', '2025-08-14 10:55:38'),
(42, 8, 'images/spot/14-08-2025-16-55-38-142972.JPG', 1, '2025-08-14 10:55:38', '2025-08-14 10:55:38'),
(43, 8, 'images/spot/14-08-2025-16-55-38-425363.JPG', 1, '2025-08-14 10:55:38', '2025-08-14 10:55:38'),
(44, 8, 'images/spot/14-08-2025-16-55-38-179595.JPG', 1, '2025-08-14 10:55:38', '2025-08-14 10:55:38'),
(45, 8, 'images/spot/14-08-2025-16-55-39-384126.JPG', 1, '2025-08-14 10:55:39', '2025-08-14 10:55:39');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) UNSIGNED NOT NULL,
  `warehouse_id` int(11) DEFAULT 1,
  `to_warehouse_id` int(11) DEFAULT 0,
  `stock_date` datetime DEFAULT NULL,
  `stock_type` varchar(255) DEFAULT NULL,
  `delivery_challan_no` varchar(200) DEFAULT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier_invoice_no` varchar(255) DEFAULT NULL,
  `purchase_price` decimal(18,2) DEFAULT 0.00,
  `stock_in_quantity` decimal(18,2) DEFAULT 0.00,
  `stock_in_unit_price` decimal(18,2) DEFAULT 0.00,
  `stock_in_discount` decimal(18,2) DEFAULT 0.00,
  `stock_in_total_amount` decimal(20,2) DEFAULT 0.00,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `stock_out_quantity` decimal(18,2) DEFAULT 0.00,
  `stock_out_unit_price` decimal(18,2) DEFAULT 0.00,
  `stock_out_discount` decimal(18,2) DEFAULT 0.00,
  `stock_out_total_amount` decimal(20,2) DEFAULT 0.00,
  `done_by` varchar(100) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `product_service_detail_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `warehouse_id`, `to_warehouse_id`, `stock_date`, `stock_type`, `delivery_challan_no`, `invoice_no`, `product_id`, `supplier_id`, `supplier_invoice_no`, `purchase_price`, `stock_in_quantity`, `stock_in_unit_price`, `stock_in_discount`, `stock_in_total_amount`, `customer_id`, `stock_out_quantity`, `stock_out_unit_price`, `stock_out_discount`, `stock_out_total_amount`, `done_by`, `remarks`, `status`, `product_service_detail_id`, `created_at`, `updated_at`) VALUES
(1, 1, 0, '2024-10-01 00:00:00', 'In', NULL, 'PUR000001', 1, 207, 'SINV#0001', '0.00', '25.00', '1500.00', '0.00', '37500.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', 'Paid', 0, NULL, '2024-10-27 10:24:30', '2024-10-27 10:24:30'),
(2, 1, 0, '2024-10-01 00:00:00', 'In', NULL, 'PUR000001', 2, 207, 'SINV#0001', '0.00', '20.00', '200.00', '0.00', '4000.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', 'Paid', 0, NULL, '2024-10-27 10:24:30', '2024-10-27 10:24:30'),
(3, 1, 0, '2024-10-05 00:00:00', 'In', NULL, 'PUR000002', 1, 207, 'SINV#0002', '0.00', '30.00', '1600.00', '0.00', '48000.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', 'Done', 0, NULL, '2024-10-27 10:25:55', '2024-10-27 10:25:55'),
(4, 1, 0, '2024-10-05 00:00:00', 'In', NULL, 'PUR000002', 2, 207, 'SINV#0002', '0.00', '25.00', '220.00', '0.00', '5500.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', 'Done', 0, NULL, '2024-10-27 10:25:55', '2024-10-27 10:25:55'),
(5, 1, 0, '2024-10-10 00:00:00', 'In', NULL, 'PUR000003', 1, 207, 'SINV#0003', '0.00', '40.00', '1700.00', '500.00', '67500.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', 'Paid', 0, NULL, '2024-10-27 10:28:40', '2024-10-27 10:28:40'),
(6, 1, 0, '2024-10-10 00:00:00', 'In', NULL, 'PUR000003', 2, 207, 'SINV#0003', '0.00', '35.00', '240.00', '400.00', '8000.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', 'Paid', 0, NULL, '2024-10-27 10:28:40', '2024-10-27 10:28:40'),
(9, 1, 0, '2024-10-25 00:00:00', 'Out', 'CHA000002', 'INV000002', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '15.00', '2000.00', '0.00', '30000.00', 'Demo', NULL, 0, NULL, '2024-10-27 10:30:31', '2024-10-27 10:30:31'),
(10, 1, 0, '2024-10-25 00:00:00', 'Out', 'CHA000002', 'INV000002', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '20.00', '300.00', '0.00', '6000.00', 'Demo', NULL, 0, NULL, '2024-10-27 10:30:31', '2024-10-27 10:30:31'),
(11, 1, 0, '2024-10-27 00:00:00', 'Out', 'CHA000003', 'INV000003', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '9.00', '1850.50', '54.00', '16601.00', 'Demo', NULL, 0, NULL, '2024-10-27 11:41:14', '2024-10-27 11:41:14'),
(12, 1, 0, '2024-10-27 00:00:00', 'Out', 'CHA000003', 'INV000003', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '7.00', '288.50', '19.00', '2001.00', 'Demo', NULL, 0, NULL, '2024-10-27 11:41:14', '2024-10-27 11:41:14'),
(13, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000004', 'INV000004', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '5.00', '2000.00', '0.00', '10000.00', 'Demo', NULL, 0, NULL, '2024-10-28 08:00:05', '2024-10-28 08:00:05'),
(14, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000004', 'INV000004', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '5.00', '300.00', '0.00', '1500.00', 'Demo', NULL, 0, NULL, '2024-10-28 08:00:05', '2024-10-28 08:00:05'),
(15, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000005', 'INV000005', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '0.50', '2000.00', '0.00', '1000.00', 'Demo', 'Remarks .....', 0, NULL, '2024-10-28 09:17:58', '2024-10-28 09:17:58'),
(16, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000006', 'INV000006', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 09:37:18', '2024-10-28 09:37:18'),
(17, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000007', 'INV000007', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 09:39:21', '2024-10-28 09:39:21'),
(18, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000008', 'INV000008', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 09:40:31', '2024-10-28 09:40:31'),
(19, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000009', 'INV000009', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 09:41:11', '2024-10-28 09:41:11'),
(20, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000010', 'INV000010', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 09:43:02', '2024-10-28 09:43:02'),
(21, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000011', 'INV000011', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 09:49:26', '2024-10-28 09:49:26'),
(22, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000012', 'INV000012', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 09:53:34', '2024-10-28 09:53:34'),
(23, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000013', 'INV000013', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 09:59:23', '2024-10-28 09:59:23'),
(24, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000014', 'INV000014', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:02:09', '2024-10-28 10:02:09'),
(25, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000015', 'INV000015', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:04:09', '2024-10-28 10:04:09'),
(26, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000016', 'INV000016', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:07:14', '2024-10-28 10:07:14'),
(27, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000017', 'INV000017', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:08:41', '2024-10-28 10:08:41'),
(28, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000018', 'INV000018', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:09:36', '2024-10-28 10:09:36'),
(29, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000019', 'INV000019', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:13:26', '2024-10-28 10:13:26'),
(30, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000020', 'INV000020', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:20:48', '2024-10-28 10:20:48'),
(31, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000021', 'INV000021', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:23:01', '2024-10-28 10:23:01'),
(32, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000022', 'INV000022', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:25:08', '2024-10-28 10:25:08'),
(33, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000023', 'INV000023', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:32:20', '2024-10-28 10:32:20'),
(34, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000024', 'INV000024', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:35:22', '2024-10-28 10:35:22'),
(35, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000025', 'INV000025', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:38:18', '2024-10-28 10:38:18'),
(36, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000026', 'INV000026', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:40:43', '2024-10-28 10:40:43'),
(37, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000027', 'INV000027', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:41:34', '2024-10-28 10:41:34'),
(38, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000028', 'INV000028', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:44:46', '2024-10-28 10:44:46'),
(39, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000029', 'INV000029', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:48:29', '2024-10-28 10:48:29'),
(40, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000030', 'INV000030', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:50:45', '2024-10-28 10:50:45'),
(41, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000031', 'INV000031', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:54:38', '2024-10-28 10:54:38'),
(42, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000032', 'INV000032', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:56:06', '2024-10-28 10:56:06'),
(43, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000033', 'INV000033', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:57:19', '2024-10-28 10:57:19'),
(44, 1, 0, '2024-10-05 00:00:00', 'Out', 'CHA000034', 'INV000034', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 10:58:22', '2024-10-28 10:58:22'),
(45, 1, 0, '2024-10-04 00:00:00', 'Out', 'CHA000035', 'INV000035', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:00:14', '2024-10-28 11:00:14'),
(46, 1, 0, '2024-10-08 00:00:00', 'Out', 'CHA000036', 'INV000036', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:00:51', '2024-10-28 11:00:51'),
(47, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000037', 'INV000037', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:14:26', '2024-10-28 11:14:26'),
(48, 1, 0, '2024-10-28 00:00:00', 'Out', 'CHA000038', 'INV000038', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:15:14', '2024-10-28 11:15:14'),
(49, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000039', 'INV000039', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:15:53', '2024-10-28 11:15:53'),
(50, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000040', 'INV000040', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:16:47', '2024-10-28 11:16:47'),
(51, 1, 0, '2024-10-16 00:00:00', 'Out', 'CHA000041', 'INV000041', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:40:23', '2024-10-28 11:40:23'),
(52, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000042', 'INV000042', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:45:50', '2024-10-28 11:45:50'),
(53, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000043', 'INV000043', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 11:48:49', '2024-10-28 11:48:49'),
(54, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000044', 'INV000044', 1, NULL, NULL, '1500.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 12:41:45', '2024-10-28 12:41:45'),
(55, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000044', 'INV000044', 2, NULL, NULL, '200.00', '0.00', '0.00', '0.00', '0.00', 208, '2.00', '300.00', '0.00', '600.00', 'Demo', NULL, 0, NULL, '2024-10-28 12:41:45', '2024-10-28 12:41:45'),
(56, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000045', 'INV000045', 2, NULL, NULL, '200.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '350.00', '0.00', '350.00', 'Demo', NULL, 0, NULL, '2024-10-28 12:56:47', '2024-10-28 12:56:47'),
(57, 1, 0, '2024-10-25 00:00:00', 'Out', 'CHA000046', 'INV000046', 1, NULL, NULL, '1500.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '1800.00', '0.00', '1800.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:00:40', '2024-10-28 13:00:40'),
(58, 1, 0, '2024-10-25 00:00:00', 'Out', 'CHA000046', 'INV000046', 2, NULL, NULL, '200.00', '0.00', '0.00', '0.00', '0.00', 208, '2.00', '350.00', '0.00', '700.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:00:40', '2024-10-28 13:00:40'),
(59, 1, 0, '2024-10-23 00:00:00', 'Out', 'CHA000047', 'INV000047', 1, NULL, NULL, '1500.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:02:33', '2024-10-28 13:02:33'),
(60, 1, 0, '2024-10-23 00:00:00', 'Out', 'CHA000047', 'INV000047', 2, NULL, NULL, '200.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:02:33', '2024-10-28 13:02:33'),
(61, 1, 0, '2024-10-01 00:00:00', 'In', NULL, 'PUR000004', 1, 207, NULL, '0.00', '1.00', '1600.00', '0.00', '1600.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:04:33', '2024-10-28 13:04:33'),
(62, 1, 0, '2024-10-01 00:00:00', 'In', NULL, 'PUR000005', 1, 207, NULL, '0.00', '1.00', '1600.00', '0.00', '1600.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:08:47', '2024-10-28 13:08:47'),
(63, 1, 0, '2024-10-01 00:00:00', 'In', NULL, 'PUR000005', 2, 207, NULL, '0.00', '1.00', '200.00', '0.00', '200.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:08:47', '2024-10-28 13:08:47'),
(64, 1, 0, '2024-10-01 00:00:00', 'In', NULL, 'PUR000006', 1, 207, NULL, '0.00', '1.00', '1600.00', '0.00', '1600.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:09:53', '2024-10-28 13:09:53'),
(65, 1, 0, '2024-10-01 00:00:00', 'In', NULL, 'PUR000006', 2, 207, NULL, '0.00', '1.00', '250.00', '0.00', '250.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:09:53', '2024-10-28 13:09:53'),
(66, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000048', 'INV000048', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:10:43', '2024-10-28 13:10:43'),
(67, 1, 0, '2024-10-01 00:00:00', 'Out', 'CHA000048', 'INV000048', 2, NULL, NULL, '250.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '300.00', '0.00', '300.00', 'Demo', NULL, 0, NULL, '2024-10-28 13:10:43', '2024-10-28 13:10:43'),
(68, NULL, 0, '2024-11-14 00:00:00', 'Out', NULL, 'RTP000001', 1, 207, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '5.00', '1500.00', '0.00', '7500.00', 'Demo', 'Paid', 0, NULL, '2024-11-14 06:43:32', '2024-11-14 06:43:32'),
(69, NULL, 0, '2024-11-14 00:00:00', 'Out', NULL, 'RTP000001', 2, 207, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '5.00', '200.00', '0.00', '1000.00', 'Demo', 'Paid', 0, NULL, '2024-11-14 06:43:32', '2024-11-14 06:43:32'),
(70, NULL, 0, '2024-11-14 00:00:00', 'Out', NULL, 'RTP000006', 1, 207, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '1.00', '1600.00', '0.00', '1600.00', 'Demo', 'Remarks .....', 0, NULL, '2024-11-14 09:50:36', '2024-11-14 09:50:36'),
(71, NULL, 0, '2024-11-14 00:00:00', 'Out', NULL, 'RTP000006', 2, 207, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '1.00', '250.00', '0.00', '250.00', 'Demo', 'Remarks .....', 0, NULL, '2024-11-14 09:50:36', '2024-11-14 09:50:36'),
(72, NULL, 0, '2024-11-14 00:00:00', 'Out', NULL, 'RTP000003', 1, 207, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '10.00', '1700.00', '500.00', '16500.00', 'Demo', 'Paid', 0, NULL, '2024-11-14 09:51:10', '2024-11-14 09:51:10'),
(73, NULL, 0, '2024-11-14 00:00:00', 'Out', NULL, 'RTP000003', 2, 207, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '15.00', '240.00', '400.00', '3200.00', 'Demo', 'Paid', 0, NULL, '2024-11-14 09:51:10', '2024-11-14 09:51:10'),
(74, 1, 0, '2024-11-17 00:00:00', 'Out', 'CHA000049', 'INV000049', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', 'Cable, Switch and Plug', 0, NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(75, 1, 0, '2024-11-17 00:00:00', 'Out', 'CHA000049', 'INV000049', 2, NULL, NULL, '250.00', '0.00', '0.00', '0.00', '0.00', 209, '2.00', '300.00', '0.00', '600.00', 'Demo', 'Cable, Switch and Plug', 0, NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(76, 1, 0, '2024-11-17 00:00:00', 'Out', 'CHA000049', 'INV000049', 5, NULL, NULL, '480.00', '0.00', '0.00', '0.00', '0.00', 209, '3.00', '590.00', '0.00', '1770.00', 'Demo', 'Cable, Switch and Plug', 0, NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(77, 1, 0, '2024-11-17 00:00:00', 'Out', 'CHA000049', 'INV000049', 13, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '200.00', '0.00', '200.00', 'Demo', 'Cable, Switch and Plug', 0, NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(78, 1, 0, '2024-11-17 00:00:00', 'Out', 'CHA000049', 'INV000049', 12, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', 'Cable, Switch and Plug', 0, NULL, '2024-11-17 09:40:49', '2024-11-17 09:40:49'),
(79, NULL, 0, '2024-11-18 00:00:00', 'Out', 'CHA000050', 'INV000050', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', 208, '2.00', '2000.00', '0.00', '4000.00', 'Demo', NULL, 0, NULL, '2024-11-18 09:34:29', '2024-11-18 09:34:29'),
(80, NULL, 0, '2024-11-18 00:00:00', 'Out', 'CHA000050', 'INV000050', 4, NULL, NULL, '40.00', '0.00', '0.00', '0.00', '0.00', 208, '3.00', '50.00', '0.00', '150.00', 'Demo', NULL, 0, NULL, '2024-11-18 09:34:29', '2024-11-18 09:34:29'),
(81, NULL, 0, '2024-11-18 00:00:00', 'Out', 'CHA000051', 'INV000051', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '2000.00', '0.00', '2000.00', 'Demo', 'Remark 1', 0, NULL, '2024-11-18 11:15:40', '2024-11-18 11:15:40'),
(82, NULL, 0, '2024-11-18 00:00:00', 'Out', 'CHA000051', 'INV000051', 13, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '200.00', '0.00', '200.00', 'Demo', 'Remark outside', 0, NULL, '2024-11-18 11:15:40', '2024-11-18 11:15:40'),
(83, NULL, 0, '2024-11-18 00:00:00', 'Out', 'CHA000051', 'INV000051', 12, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '300.00', '0.00', '300.00', 'Demo', 'Remark con', 0, NULL, '2024-11-18 11:15:40', '2024-11-18 11:15:40'),
(84, NULL, 0, '2024-11-19 00:00:00', 'Out', 'CHA000052', 'INV000052', 10, NULL, NULL, '700.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '1000.00', '0.00', '1000.00', 'Demo', 'for service', 0, 42, '2024-11-19 09:25:50', '2024-11-19 09:25:50'),
(85, NULL, 0, '2024-11-19 00:00:00', 'Out', 'CHA000052', 'INV000052', 13, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '200.00', '0.00', '200.00', 'Demo', 'Out Product', 0, 42, '2024-11-19 09:25:50', '2024-11-19 09:25:50'),
(86, NULL, 0, '2024-11-19 00:00:00', 'Out', 'CHA000052', 'INV000052', 12, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '1.00', '300.00', '0.00', '300.00', 'Demo', 'remarks..', 0, 42, '2024-11-19 09:25:50', '2024-11-19 09:25:50'),
(87, NULL, 0, '2024-11-21 00:00:00', 'Out', NULL, 'RTS000049', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '2000.00', '0.00', '2000.00', 'Demo', 'Cable, Switch and Plug --- return', 0, NULL, '2024-11-21 11:31:02', '2024-11-21 11:31:02'),
(88, NULL, 0, '2024-11-21 00:00:00', 'Out', NULL, 'RTS000049', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '300.00', '0.00', '300.00', 'Demo', 'Cable, Switch and Plug --- return', 0, NULL, '2024-11-21 11:31:02', '2024-11-21 11:31:02'),
(89, NULL, 0, '2024-11-21 00:00:00', 'Out', NULL, 'RTS000049', 5, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '590.00', '0.00', '590.00', 'Demo', 'Cable, Switch and Plug --- return', 0, NULL, '2024-11-21 11:31:02', '2024-11-21 11:31:02'),
(90, 1, 0, '2024-11-15 00:00:00', 'In', NULL, 'PUR000007', 1, 207, NULL, '0.00', '5.00', '1600.00', '0.00', '8000.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-25 07:10:40', '2024-11-25 07:10:40'),
(102, 1, 2, '2024-11-25 00:00:00', 'Out', 'CHA000058', 'TRN000003', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', NULL, '1.00', '2000.00', '0.00', '2000.00', 'Demo', 'Remarks .....', 1, NULL, '2024-11-25 11:47:39', '2024-11-25 11:47:39'),
(103, 1, 2, '2024-11-25 00:00:00', 'Out', 'CHA000058', 'TRN000003', 2, NULL, NULL, '250.00', '0.00', '0.00', '0.00', '0.00', NULL, '1.00', '300.00', '0.00', '300.00', 'Demo', 'Remarks .....', 0, NULL, '2024-11-25 11:47:39', '2024-11-25 11:47:39'),
(104, 1, 3, '2024-11-26 00:00:00', 'Out', 'CHA000059', 'TRN000004', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', NULL, '1.00', '2000.00', '0.00', '2000.00', 'Demo', 'Remarks .....', 1, NULL, '2024-11-26 07:46:04', '2024-11-26 07:46:04'),
(105, 1, 3, '2024-11-26 00:00:00', 'Out', 'CHA000059', 'TRN000004', 2, NULL, NULL, '250.00', '0.00', '0.00', '0.00', '0.00', NULL, '2.00', '300.00', '0.00', '600.00', 'Demo', 'Remarks .....', 1, NULL, '2024-11-26 07:46:04', '2024-11-26 07:46:04'),
(106, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 1, 207, NULL, '0.00', '5.00', '1600.00', '0.00', '8000.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(107, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 11, 207, NULL, '0.00', '5.00', '1500.00', '0.00', '7500.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(108, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 10, 207, NULL, '0.00', '5.00', '700.00', '0.00', '3500.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(109, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 9, 207, NULL, '0.00', '5.00', '700.00', '0.00', '3500.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(110, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 8, 207, NULL, '0.00', '5.00', '147.00', '0.00', '735.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(111, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 7, 207, NULL, '0.00', '5.00', '184.00', '0.00', '920.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(112, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 6, 207, NULL, '0.00', '5.00', '390.00', '0.00', '1950.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(113, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 5, 207, NULL, '0.00', '5.00', '480.00', '0.00', '2400.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(114, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 4, 207, NULL, '0.00', '5.00', '40.00', '0.00', '200.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(115, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 3, 207, NULL, '0.00', '5.00', '700.00', '0.00', '3500.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(116, 1, 0, '2024-11-26 00:00:00', 'In', NULL, 'PUR000008', 2, 207, NULL, '0.00', '5.00', '250.00', '0.00', '1250.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 0, NULL, '2024-11-26 10:41:47', '2024-11-26 10:41:47'),
(119, 3, 0, '2024-11-26 00:00:00', 'In', NULL, 'TRN000004', 1, NULL, NULL, '0.00', '1.00', '2000.00', '0.00', '2000.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 1, NULL, '2024-11-26 12:56:25', '2024-11-26 12:56:25'),
(120, 3, 0, '2024-11-26 00:00:00', 'In', NULL, 'TRN000004', 2, NULL, NULL, '0.00', '2.00', '300.00', '0.00', '600.00', NULL, '0.00', '0.00', '0.00', '0.00', 'Demo', NULL, 1, NULL, '2024-11-26 13:00:37', '2024-11-26 13:00:37'),
(124, 1, 0, '2024-11-01 00:00:00', 'Out', 'CHA000060', 'INV000053', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', 208, '5.00', '2000.00', '0.00', '10000.00', 'Demo', 'Remarks', 0, NULL, '2024-11-28 06:44:18', '2024-11-28 06:44:18'),
(134, 1, 0, '2024-12-02 00:00:00', 'Out', 'CHA000063', 'INV000056', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', 210, '1.00', '2000.00', '0.00', '2000.00', 'Demo', NULL, 1, NULL, '2024-12-02 06:31:45', '2024-12-02 06:31:45'),
(135, 1, 0, '2024-12-02 00:00:00', 'Out', 'CHA000064', 'INV000057', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', 217, '2.00', '2000.00', '0.00', '4000.00', 'Demo', NULL, 1, NULL, '2024-12-02 07:31:40', '2024-12-02 07:31:40'),
(136, 1, 0, '2024-12-02 00:00:00', 'Out', 'CHA000065', 'INV000058', 3, NULL, NULL, '700.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '1000.00', '0.00', '1000.00', 'Demo', NULL, 1, NULL, '2024-12-02 07:33:43', '2024-12-02 07:33:43'),
(137, 1, 0, '2024-12-02 00:00:00', 'Out', 'CHA000066', 'INV000059', 4, NULL, NULL, '40.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '50.00', '0.00', '50.00', 'Demo', NULL, 1, NULL, '2024-12-02 07:37:30', '2024-12-02 07:37:30'),
(138, 1, 0, '2024-12-02 00:00:00', 'Out', 'CHA000067', 'INV000060', 3, NULL, NULL, '700.00', '0.00', '0.00', '0.00', '0.00', 209, '1.00', '1000.00', '0.00', '1000.00', 'Demo', NULL, 1, NULL, '2024-12-02 07:39:09', '2024-12-02 07:39:09'),
(139, 1, 0, '2024-12-02 00:00:00', 'Out', 'CHA000068', 'INV000061', 1, NULL, NULL, '1600.00', '0.00', '0.00', '0.00', '0.00', 208, '2.00', '2000.00', '0.00', '4000.00', 'Demo', NULL, 1, NULL, '2024-12-02 07:54:08', '2024-12-02 07:54:08'),
(142, 1, 0, '2024-10-15 00:00:00', 'Out', 'CHA000001', 'INV000001', 1, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '5.00', '2000.00', '0.00', '10000.00', 'Demo', 'Remarks .....', 1, NULL, '2024-12-11 11:48:09', '2024-12-11 11:48:09'),
(143, 1, 0, '2024-10-15 00:00:00', 'Out', 'CHA000001', 'INV000001', 2, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 208, '10.00', '300.00', '0.00', '3000.00', 'Demo', 'Remarks .....', 1, NULL, '2024-12-11 11:48:09', '2024-12-11 11:48:09');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `ac_id` int(10) DEFAULT NULL,
  `supplier_code` varchar(255) DEFAULT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_proprietor_name` varchar(255) DEFAULT NULL,
  `supplier_mobile` varchar(255) DEFAULT NULL,
  `supplier_email` varchar(255) DEFAULT NULL,
  `supplier_address` varchar(255) DEFAULT NULL,
  `representative_name` varchar(255) DEFAULT NULL,
  `representative_mobile` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `done_by` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `ac_id`, `supplier_code`, `supplier_name`, `supplier_proprietor_name`, `supplier_mobile`, `supplier_email`, `supplier_address`, `representative_name`, `representative_mobile`, `start_date`, `profile_img`, `status`, `done_by`, `created_at`, `updated_at`) VALUES
(1, 207, '01', 'Perfume World', NULL, '01600000000', NULL, 'Dhaka, Bangladesh', NULL, NULL, NULL, NULL, 1, 'Demo', '2024-10-16 11:27:46', '2024-10-16 11:27:46'),
(2, 211, 's10', 'Mehera Service Station', NULL, '0175412125', NULL, 'Kuril', NULL, NULL, NULL, NULL, 1, 'Demo', '2024-10-31 09:28:44', '2024-10-31 09:28:44'),
(3, 212, 's11', 'Asian Pharmacy', NULL, '0175412125', NULL, 'Kuril', NULL, NULL, NULL, NULL, 1, 'Demo', '2024-10-31 09:29:59', '2024-10-31 09:29:59'),
(4, 213, 's12', 'Nabil Medicine', NULL, '0175412125', NULL, 'Kuril', NULL, NULL, NULL, NULL, 1, 'Demo', '2024-10-31 09:30:31', '2024-10-31 09:30:31'),
(5, 214, 's13', 'Goodluck CNG Filling Station', NULL, '0175412125', NULL, 'Kuril', NULL, NULL, NULL, NULL, 1, 'Demo', '2024-10-31 09:31:21', '2024-10-31 09:31:21'),
(6, 215, 's14', 'Anwar CNG', NULL, '0175412125', NULL, 'Kuril', NULL, NULL, NULL, NULL, 1, 'Demo', '2024-10-31 09:32:00', '2024-10-31 09:32:00'),
(7, 216, 's15', 'Gatsby Wear', NULL, '0175412125', NULL, 'Kuril', NULL, NULL, NULL, NULL, 1, 'Demo', '2024-10-31 09:33:20', '2024-10-31 09:33:20'),
(8, 225, 'S-1002', 'Supplier Name 1002', 'Supplier Proprietor Name 1002', '01681952640', 'supplier@gmail.com', 'Gulshan, Dhaka, Bangladesh', NULL, NULL, NULL, NULL, 1, 'Demo', '2024-11-11 09:53:04', '2024-11-11 11:18:58');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_ledgers`
--

CREATE TABLE `supplier_ledgers` (
  `id` int(10) UNSIGNED NOT NULL,
  `ledger_date` date NOT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier_invoice_no` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `debit` decimal(18,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(18,2) NOT NULL DEFAULT 0.00,
  `payment_type` varchar(50) DEFAULT NULL,
  `bank_name` varchar(50) DEFAULT NULL,
  `cheque_no` varchar(50) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `bkash_merchant_number` varchar(50) DEFAULT NULL,
  `bkash_payment_number` varchar(50) DEFAULT NULL,
  `bkash_trx_id` varchar(50) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `is_previous_due` tinyint(4) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `done_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `fb_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `linkdin_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT 1,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `designation`, `start_date`, `end_date`, `image`, `description`, `fb_url`, `twitter_url`, `linkdin_url`, `instagram_url`, `order`, `status`, `created_at`, `updated_at`) VALUES
(4, 'Saddam Hafiz Alif', 'Scientist', '2024-07-17', NULL, 'images/team/17-07-2025-18-20-55-team-4.jpg', '<p>fhggfhgfh dfghfdhfg vbhgh</p>', NULL, NULL, NULL, NULL, 2, 1, '2025-07-17 11:57:25', '2025-08-24 07:17:41'),
(5, 'Asif Uzzaman', 'Manager', '2025-02-17', NULL, 'images/team/17-07-2025-18-20-42-team-2.jpg', '<p>hjhmjh ghjhjm des</p>', NULL, NULL, NULL, NULL, 1, 1, '2025-07-17 11:58:25', '2025-08-24 07:17:18');

-- --------------------------------------------------------

--
-- Table structure for table `terminations`
--

CREATE TABLE `terminations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL DEFAULT 0,
  `notice_date` date NOT NULL,
  `termination_date` date NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terminations`
--

INSERT INTO `terminations` (`id`, `employee_id`, `notice_date`, `termination_date`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-09-04', '2023-09-14', 'xcvcxvfdgfd', 1, '2023-09-03 23:56:01', '2023-09-03 23:56:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `approved_index` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_photo_path`, `email_verified_at`, `password`, `avatar`, `remember_token`, `approved_index`, `created_at`, `updated_at`, `last_login_at`, `last_login_ip`) VALUES
(1, 'Demo', 'demo@demo.com', NULL, '2023-07-25 04:29:51', '$2y$10$j8vCvW/x184cUEDz9BYjsuhrQq1dDRYApVcJpfYWD4ZEsoxI6.Upa', NULL, NULL, 0, '2023-07-25 04:29:51', '2025-08-31 05:21:31', '2025-08-31 11:21:31', '192.168.10.3'),
(27, 'Admin', 'admin@perfume.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 0, '2023-08-14 04:18:35', '2024-10-31 12:36:21', '2024-10-31 18:36:21', '192.168.10.3'),
(29, 'Masum', 'masum.azon@gmail.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, 'Ha2MUS29iITg3WlGvHlSJXmT13RjJ94W3wPYiKU9OCkyWbjhbu7bdS3H8fQy', 0, '2023-09-19 01:43:09', '2024-05-30 06:24:38', '2024-01-16 10:23:52', '192.168.10.3'),
(30, 'Admin Sonali', 'admin@sonali.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 0, '2023-09-19 22:45:46', '2024-10-16 15:46:36', '2024-10-16 11:46:36', '106.0.54.74'),
(31, 'Mr. Reza', 'reza@gmail.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 0, '2023-09-19 22:52:39', '2023-12-24 01:42:22', '2023-12-24 07:42:22', '192.168.10.2'),
(32, 'Perfume PLC', 'perfume@perfume.com', NULL, NULL, '$2y$10$RzDZI8QCHkWhkNZssa7W2OZ.vkrKB7Pay04iPcn9BsCXGHcji5kam', NULL, NULL, 0, '2023-10-04 06:13:43', '2024-05-16 08:38:27', '2024-05-16 14:38:02', '192.168.10.3'),
(33, 'HR', 'hr@demo.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 0, '2023-10-10 06:01:05', '2024-04-09 01:07:57', '2024-04-09 07:07:57', '127.0.0.1'),
(34, 'Mr. Salek', 'salek@gmail.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 0, '2023-10-10 06:11:15', '2023-11-20 05:38:45', '2023-11-20 11:38:45', '192.168.10.2'),
(35, 'CEO', 'ceo@perfume.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 2, '2023-10-12 00:21:57', '2024-07-04 12:20:44', '2024-07-04 18:20:44', '192.168.10.2'),
(38, 'Chemists', 'chemists@perfume.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 0, '2023-11-12 03:26:55', '2024-05-27 09:34:32', '2024-05-27 15:34:32', '192.168.10.3'),
(39, 'Store Incharge', 'storeincharge@perfume.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 3, '2023-11-20 05:11:02', '2024-06-04 09:38:16', '2024-06-04 15:38:16', '192.168.10.3'),
(41, 'Production Manager', 'promanager@perfume.com', NULL, NULL, '$2y$10$4XPXQwS.SZzqe6SD/Tn97.mXWcl8Kq0Fk/x/MocguwzlSRwDHgOFq', NULL, NULL, 1, '2023-11-22 01:52:58', '2024-07-03 05:43:43', '2024-07-03 11:43:43', '192.168.10.2'),
(42, 'Kazi Jahidul Haque', 'kaium@gmail.com', NULL, NULL, '$2y$10$iAInIWg.AMHdTQxa2Zusv.0J4MmRfjEF08bfyrqAch6sGwD44odaO', NULL, NULL, 0, '2025-01-21 07:23:08', '2025-01-21 07:23:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `link` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `link`, `created_at`, `updated_at`) VALUES
(4, 'https://www.youtube.com/watch?v=SHnVt0jq27g&t=6s', '2025-07-09 12:36:02', '2025-07-09 12:36:02'),
(5, 'https://www.youtube.com/watch?v=MHJzCPlqeWQ&t=1s', '2025-07-09 12:37:12', '2025-07-09 12:37:12'),
(6, 'https://www.youtube.com/watch?v=flB9CKzUDe4', '2025-07-09 12:37:32', '2025-07-09 12:37:32'),
(7, 'https://www.youtube.com/watch?v=flB9CKzUDe4', '2025-07-09 12:38:13', '2025-07-09 12:38:13');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL,
  `company_code` varchar(200) DEFAULT '01',
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `done_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `company_code`, `code`, `name`, `address`, `description`, `status`, `done_by`, `created_at`, `updated_at`) VALUES
(1, '01', '001', 'Head Office (Main)', NULL, 'Main Warehouse', 1, 'Demo', '2024-09-03 07:48:46', '2024-09-11 08:27:14'),
(2, '01', '002', 'Dhaka Branch', NULL, 'Mirpur Warehouse', 1, 'Demo', '2024-09-03 08:07:35', '2024-10-21 10:13:20'),
(3, '01', '003', 'Gulshan Branch', NULL, 'Gulshan Warehouse', 1, 'Demo', '2024-09-03 08:40:31', '2024-09-10 06:22:39'),
(6, '01', '004', 'Dhanmondi Branch', NULL, 'Description  ..', 1, 'Demo', '2024-09-11 08:27:34', '2024-09-11 08:27:49'),
(7, '01', '005', 'Mirpur Branch', NULL, 'Description', 1, 'Demo', '2024-11-24 09:33:49', '2024-11-24 09:34:15');

-- --------------------------------------------------------

--
-- Table structure for table `weekend_days`
--

CREATE TABLE `weekend_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `weekend_day` varchar(50) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `weekend_days`
--

INSERT INTO `weekend_days` (`id`, `weekend_day`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Friday', 'Demo', '2024-01-11 02:08:33', '2024-01-11 02:08:33'),
(2, 'Saturday', 'Demo', '2024-01-11 02:08:41', '2024-01-11 02:08:41');

-- --------------------------------------------------------

--
-- Table structure for table `work_times`
--

CREATE TABLE `work_times` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `work_times`
--

INSERT INTO `work_times` (`id`, `start_time`, `end_time`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '09:00:00', '17:00:00', 'Demo', '2024-01-11 00:21:33', '2024-01-11 00:22:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `additionalservices`
--
ALTER TABLE `additionalservices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcement_details`
--
ALTER TABLE `announcement_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CustomerID` (`customer_id`),
  ADD KEY `RoomID` (`room_id`);

--
-- Indexes for table `bookingservices`
--
ALTER TABLE `bookingservices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `BookingID` (`booking_id`),
  ADD KEY `ServiceID` (`service_id`);

--
-- Indexes for table `break_times`
--
ALTER TABLE `break_times`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checkincheckout`
--
ALTER TABLE `checkincheckout`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `BookingID` (`BookingID`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_ledgers_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `damage_products`
--
ALTER TABLE `damage_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_delayin_earlyouts`
--
ALTER TABLE `employee_delayin_earlyouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_education`
--
ALTER TABLE `employee_education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_job_histories`
--
ALTER TABLE `employee_job_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_job_responsibilities`
--
ALTER TABLE `employee_job_responsibilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_leave_entries`
--
ALTER TABLE `employee_leave_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_leave_settings`
--
ALTER TABLE `employee_leave_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_ledgers`
--
ALTER TABLE `employee_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_performances`
--
ALTER TABLE `employee_performances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_salaries`
--
ALTER TABLE `employee_salaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_branches`
--
ALTER TABLE `emp_branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_departments`
--
ALTER TABLE `emp_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_designations`
--
ALTER TABLE `emp_designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_grades`
--
ALTER TABLE `emp_grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_lines`
--
ALTER TABLE `emp_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_postings`
--
ALTER TABLE `emp_postings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_quite_types`
--
ALTER TABLE `emp_quite_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_salary_sections`
--
ALTER TABLE `emp_salary_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_sections`
--
ALTER TABLE `emp_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_types`
--
ALTER TABLE `emp_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `finance_accounts`
--
ALTER TABLE `finance_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finance_groups`
--
ALTER TABLE `finance_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_code` (`group_code`);

--
-- Indexes for table `finance_transactions`
--
ALTER TABLE `finance_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genrate_payslip_options`
--
ALTER TABLE `genrate_payslip_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_heads`
--
ALTER TABLE `income_heads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoiceno`
--
ALTER TABLE `invoiceno`
  ADD PRIMARY KEY (`invoice_no_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `BookingID` (`booking_id`);

--
-- Indexes for table `late_times`
--
ALTER TABLE `late_times`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_options`
--
ALTER TABLE `loan_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `monthly_salaries`
--
ALTER TABLE `monthly_salaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_salary_details`
--
ALTER TABLE `monthly_salary_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_payments`
--
ALTER TABLE `other_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `overtimes`
--
ALTER TABLE `overtimes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `BookingID` (`BookingID`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_formulas`
--
ALTER TABLE `payroll_formulas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_heads`
--
ALTER TABLE `payroll_heads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_types`
--
ALTER TABLE `payslip_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_slips`
--
ALTER TABLE `pay_slips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performance_types`
--
ALTER TABLE `performance_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_bill_of_matrials`
--
ALTER TABLE `production_bill_of_matrials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_supplier_id_foreign` (`supplier_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_color_id_foreign` (`color_id`),
  ADD KEY `products_size_id_foreign` (`size_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `product_brands`
--
ALTER TABLE `product_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_colors`
--
ALTER TABLE `product_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_fragrances`
--
ALTER TABLE `product_fragrances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_orders`
--
ALTER TABLE `product_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `product_order_details`
--
ALTER TABLE `product_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_id` (`product_order_id`);

--
-- Indexes for table `product_order_details_chains`
--
ALTER TABLE `product_order_details_chains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_details_id` (`product_order_details_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indexes for table `product_services`
--
ALTER TABLE `product_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_service_details`
--
ALTER TABLE `product_service_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sub_categories`
--
ALTER TABLE `product_sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_sub_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_units`
--
ALTER TABLE `product_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resignations`
--
ALTER TABLE `resignations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `RoomNumber` (`room_number`);

--
-- Indexes for table `room_details`
--
ALTER TABLE `room_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_type_name` (`type_name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `set_salaries`
--
ALTER TABLE `set_salaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smslogs`
--
ALTER TABLE `smslogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CustomerID` (`customer_id`);

--
-- Indexes for table `spots`
--
ALTER TABLE `spots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spot_details`
--
ALTER TABLE `spot_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_supplier_id_foreign` (`supplier_id`),
  ADD KEY `stocks_product_id_foreign` (`product_id`),
  ADD KEY `stocks_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_ledgers_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminations`
--
ALTER TABLE `terminations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekend_days`
--
ALTER TABLE `weekend_days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_times`
--
ALTER TABLE `work_times`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `additionalservices`
--
ALTER TABLE `additionalservices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `announcement_details`
--
ALTER TABLE `announcement_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1724;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookingservices`
--
ALTER TABLE `bookingservices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `break_times`
--
ALTER TABLE `break_times`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `checkincheckout`
--
ALTER TABLE `checkincheckout`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `damage_products`
--
ALTER TABLE `damage_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `deductions`
--
ALTER TABLE `deductions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `employee_delayin_earlyouts`
--
ALTER TABLE `employee_delayin_earlyouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employee_education`
--
ALTER TABLE `employee_education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee_job_histories`
--
ALTER TABLE `employee_job_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_job_responsibilities`
--
ALTER TABLE `employee_job_responsibilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_leave_entries`
--
ALTER TABLE `employee_leave_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_leave_settings`
--
ALTER TABLE `employee_leave_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employee_ledgers`
--
ALTER TABLE `employee_ledgers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee_performances`
--
ALTER TABLE `employee_performances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_salaries`
--
ALTER TABLE `employee_salaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `emp_branches`
--
ALTER TABLE `emp_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `emp_departments`
--
ALTER TABLE `emp_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `emp_designations`
--
ALTER TABLE `emp_designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `emp_grades`
--
ALTER TABLE `emp_grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `emp_lines`
--
ALTER TABLE `emp_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `emp_postings`
--
ALTER TABLE `emp_postings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `emp_quite_types`
--
ALTER TABLE `emp_quite_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_details`
--
ALTER TABLE `room_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `smslogs`
--
ALTER TABLE `smslogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spots`
--
ALTER TABLE `spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `spot_details`
--
ALTER TABLE `spot_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_table_event` ON SCHEDULE EVERY 12 HOUR STARTS '2025-02-13 17:58:38' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE bookings
SET Booking_status = 2
WHERE Booking_status = 0
AND created_at <= NOW() - INTERVAL 12 HOUR$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
