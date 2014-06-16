--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- Name: smesys; Type: SCHEMA; Schema: -; Owner: smesys
--

CREATE SCHEMA smesys;


ALTER SCHEMA smesys OWNER TO smesys;

SET search_path = smesys, pg_catalog;

--
-- Name: status; Type: TYPE; Schema: smesys; Owner: smesys
--

CREATE TYPE status AS ENUM (
    'active',
    'inactive'
);


ALTER TYPE smesys.status OWNER TO smesys;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: com_info; Type: TABLE; Schema: smesys; Owner: smesys; Tablespace: 
--

CREATE TABLE com_info (
    com_name character varying(150) NOT NULL,
    com_businessno character varying NOT NULL,
    com_address character varying,
    com_tel character varying,
    com_fax character varying,
    com_zipcode character varying,
    com_vat numeric DEFAULT 1
);


ALTER TABLE smesys.com_info OWNER TO smesys;

--
-- Name: customers; Type: TABLE; Schema: smesys; Owner: smesys; Tablespace: 
--

CREATE TABLE customers (
    customer_id bigint NOT NULL,
    cust_name character varying(500) NOT NULL,
    cust_tel character varying(12),
    cust_fax character varying(12),
    cust_mobile character varying(10),
    cust_email character varying(100),
    cust_address character varying(200)
);


ALTER TABLE smesys.customers OWNER TO smesys;

--
-- Name: customers_customer_id_seq; Type: SEQUENCE; Schema: smesys; Owner: smesys
--

CREATE SEQUENCE customers_customer_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE smesys.customers_customer_id_seq OWNER TO smesys;

--
-- Name: customers_customer_id_seq; Type: SEQUENCE OWNED BY; Schema: smesys; Owner: smesys
--

ALTER SEQUENCE customers_customer_id_seq OWNED BY customers.customer_id;


--
-- Name: items; Type: TABLE; Schema: smesys; Owner: smesys; Tablespace: 
--

CREATE TABLE items (
    item_id bigint NOT NULL,
    item_name character varying(1000),
    stock integer,
    supplier_id bigint,
    brand character varying(30),
    last_restock_date timestamp with time zone DEFAULT now(),
    unit_cost numeric,
    unit_price numeric,
    CONSTRAINT positive_stock CHECK ((stock >= 0))
);


ALTER TABLE smesys.items OWNER TO smesys;

--
-- Name: items_item_id_seq; Type: SEQUENCE; Schema: smesys; Owner: smesys
--

CREATE SEQUENCE items_item_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE smesys.items_item_id_seq OWNER TO smesys;

--
-- Name: items_item_id_seq; Type: SEQUENCE OWNED BY; Schema: smesys; Owner: smesys
--

ALTER SEQUENCE items_item_id_seq OWNED BY items.item_id;


--
-- Name: order_items; Type: TABLE; Schema: smesys; Owner: smesys; Tablespace: 
--

CREATE TABLE order_items (
    order_id bigint NOT NULL,
    item_id bigint NOT NULL,
    amount integer,
    sameitem character varying(40),
    price numeric DEFAULT 0 NOT NULL
);


ALTER TABLE smesys.order_items OWNER TO smesys;

--
-- Name: TABLE order_items; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON TABLE order_items IS 'item list for each order';


--
-- Name: COLUMN order_items.sameitem; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON COLUMN order_items.sameitem IS 'record the items which have the same brand and name';


--
-- Name: orders; Type: TABLE; Schema: smesys; Owner: smesys; Tablespace: 
--

CREATE TABLE orders (
    order_id bigint NOT NULL,
    customer_id bigint,
    update_time timestamp with time zone DEFAULT transaction_timestamp()
);


ALTER TABLE smesys.orders OWNER TO smesys;

--
-- Name: orders_order_id_seq; Type: SEQUENCE; Schema: smesys; Owner: smesys
--

CREATE SEQUENCE orders_order_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE smesys.orders_order_id_seq OWNER TO smesys;

--
-- Name: orders_order_id_seq; Type: SEQUENCE OWNED BY; Schema: smesys; Owner: smesys
--

ALTER SEQUENCE orders_order_id_seq OWNED BY orders.order_id;


--
-- Name: restock_history; Type: TABLE; Schema: smesys; Owner: smesys; Tablespace: 
--

CREATE TABLE restock_history (
    uid integer NOT NULL,
    restock_time timestamp with time zone DEFAULT now() NOT NULL,
    item_id bigint NOT NULL,
    previous_stock integer NOT NULL,
    new_arrival integer NOT NULL,
    previous_cost numeric NOT NULL,
    new_cost numeric,
    supplier_id bigint NOT NULL
);


ALTER TABLE smesys.restock_history OWNER TO smesys;

--
-- Name: TABLE restock_history; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON TABLE restock_history IS 'record the history of restock';


--
-- Name: COLUMN restock_history.previous_stock; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON COLUMN restock_history.previous_stock IS 'num of stock before restock';


--
-- Name: COLUMN restock_history.previous_cost; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON COLUMN restock_history.previous_cost IS 'price of the targeted item before restock';


--
-- Name: COLUMN restock_history.new_cost; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON COLUMN restock_history.new_cost IS 'price of the targeted item in restock operation';


--
-- Name: sme_access; Type: TABLE; Schema: smesys; Owner: smesys; Tablespace: 
--

CREATE TABLE sme_access (
    username character varying(50) NOT NULL,
    password character varying(200) NOT NULL,
    description character varying(500),
    uid integer NOT NULL,
    main_order boolean DEFAULT false NOT NULL,
    main_inventory boolean DEFAULT false NOT NULL,
    firstlogin boolean DEFAULT true NOT NULL,
    lastupdate timestamp with time zone DEFAULT now() NOT NULL,
    main_user boolean DEFAULT false NOT NULL
);


ALTER TABLE smesys.sme_access OWNER TO smesys;

--
-- Name: TABLE sme_access; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON TABLE sme_access IS 'access control table';


--
-- Name: sme_access_UID_seq; Type: SEQUENCE; Schema: smesys; Owner: smesys
--

CREATE SEQUENCE "sme_access_UID_seq"
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE smesys."sme_access_UID_seq" OWNER TO smesys;

--
-- Name: sme_access_UID_seq; Type: SEQUENCE OWNED BY; Schema: smesys; Owner: smesys
--

ALTER SEQUENCE "sme_access_UID_seq" OWNED BY sme_access.uid;


--
-- Name: suppliers; Type: TABLE; Schema: smesys; Owner: smesys; Tablespace: 
--

CREATE TABLE suppliers (
    supplier_id bigint NOT NULL,
    suppl_name character varying(500),
    business_num character varying(30),
    suppl_tel character varying(12),
    suppl_fax character varying(12),
    suppl_mobile character varying(10),
    suppl_email character varying(100),
    suppl_address character varying(200),
    suppl_status status DEFAULT 'active'::status NOT NULL
);


ALTER TABLE smesys.suppliers OWNER TO smesys;

--
-- Name: TABLE suppliers; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON TABLE suppliers IS 'supplier information';


--
-- Name: COLUMN suppliers.suppl_status; Type: COMMENT; Schema: smesys; Owner: smesys
--

COMMENT ON COLUMN suppliers.suppl_status IS 'use customized enum type ''status''';


--
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE; Schema: smesys; Owner: smesys
--

CREATE SEQUENCE suppliers_supplier_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE smesys.suppliers_supplier_id_seq OWNER TO smesys;

--
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE OWNED BY; Schema: smesys; Owner: smesys
--

ALTER SEQUENCE suppliers_supplier_id_seq OWNED BY suppliers.supplier_id;


--
-- Name: customer_id; Type: DEFAULT; Schema: smesys; Owner: smesys
--

ALTER TABLE customers ALTER COLUMN customer_id SET DEFAULT nextval('customers_customer_id_seq'::regclass);


--
-- Name: item_id; Type: DEFAULT; Schema: smesys; Owner: smesys
--

ALTER TABLE items ALTER COLUMN item_id SET DEFAULT nextval('items_item_id_seq'::regclass);


--
-- Name: order_id; Type: DEFAULT; Schema: smesys; Owner: smesys
--

ALTER TABLE orders ALTER COLUMN order_id SET DEFAULT nextval('orders_order_id_seq'::regclass);


--
-- Name: uid; Type: DEFAULT; Schema: smesys; Owner: smesys
--

ALTER TABLE sme_access ALTER COLUMN uid SET DEFAULT nextval('"sme_access_UID_seq"'::regclass);


--
-- Name: supplier_id; Type: DEFAULT; Schema: smesys; Owner: smesys
--

ALTER TABLE suppliers ALTER COLUMN supplier_id SET DEFAULT nextval('suppliers_supplier_id_seq'::regclass);


--
-- Name: businessno_unique; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY suppliers
    ADD CONSTRAINT businessno_unique UNIQUE (business_num);


--
-- Name: com_info_pkey; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY com_info
    ADD CONSTRAINT com_info_pkey PRIMARY KEY (com_businessno);


--
-- Name: cust_email_unique; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY customers
    ADD CONSTRAINT cust_email_unique UNIQUE (cust_email);


--
-- Name: customers_pkey; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (customer_id);


--
-- Name: items_pkey; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY items
    ADD CONSTRAINT items_pkey PRIMARY KEY (item_id);


--
-- Name: order_items_pkey; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_pkey PRIMARY KEY (order_id, item_id);


--
-- Name: orders_pkey; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (order_id);


--
-- Name: sme_access_pkey; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY sme_access
    ADD CONSTRAINT sme_access_pkey PRIMARY KEY (uid);


--
-- Name: sme_access_username_key; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY sme_access
    ADD CONSTRAINT sme_access_username_key UNIQUE (username);


--
-- Name: suppliers_pkey; Type: CONSTRAINT; Schema: smesys; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (supplier_id);


--
-- Name: itemlist_item_id_fkey; Type: FK CONSTRAINT; Schema: smesys; Owner: smesys
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT itemlist_item_id_fkey FOREIGN KEY (item_id) REFERENCES items(item_id);


--
-- Name: itemlist_order_id_fkey; Type: FK CONSTRAINT; Schema: smesys; Owner: smesys
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT itemlist_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(order_id);


--
-- Name: items_supplier_id_fkey; Type: FK CONSTRAINT; Schema: smesys; Owner: smesys
--

ALTER TABLE ONLY items
    ADD CONSTRAINT items_supplier_id_fkey FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id);


--
-- Name: orders_customer_id_fkey; Type: FK CONSTRAINT; Schema: smesys; Owner: smesys
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_customer_id_fkey FOREIGN KEY (customer_id) REFERENCES customers(customer_id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

-- Insert the very first user of the system
INSERT INTO smesys.sme_access(username, "password", description, main_order, main_inventory, 
            firstlogin, lastupdate, main_user)
    VALUES ('admin', MD5('test'), 'first admin user', true, true, true, now(), true);
