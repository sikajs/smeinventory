--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: status; Type: TYPE; Schema: public; Owner: smesys
--

CREATE TYPE status AS ENUM (
    'active',
    'inactive'
);


ALTER TYPE public.status OWNER TO smesys;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: adjust_history; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE adjust_history (
    uid integer NOT NULL,
    adjust_time timestamp with time zone DEFAULT now() NOT NULL,
    item_id bigint NOT NULL,
    previous_stock integer NOT NULL,
    new_stock integer NOT NULL,
    reason character varying(200)
);


ALTER TABLE public.adjust_history OWNER TO smesys;

--
-- Name: TABLE adjust_history; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON TABLE adjust_history IS 'record the history of stock adjustment';


--
-- Name: COLUMN adjust_history.uid; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN adjust_history.uid IS 'The user who adjust inventory';


--
-- Name: COLUMN adjust_history.previous_stock; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN adjust_history.previous_stock IS 'Stock number before adjustment';


--
-- Name: COLUMN adjust_history.new_stock; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN adjust_history.new_stock IS 'Stock number after adjustment';


--
-- Name: COLUMN adjust_history.reason; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN adjust_history.reason IS 'The reason why we need to adjust the stock';


--
-- Name: com_info; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
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


ALTER TABLE public.com_info OWNER TO smesys;

--
-- Name: cro_cro_id_seq; Type: SEQUENCE; Schema: public; Owner: smesys
--

CREATE SEQUENCE cro_cro_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cro_cro_id_seq OWNER TO smesys;

--
-- Name: cro_cro_id_seq; Type: SEQUENCE SET; Schema: public; Owner: smesys
--

SELECT pg_catalog.setval('cro_cro_id_seq', 163, true);


--
-- Name: cro; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE cro (
    cro_id bigint DEFAULT nextval('cro_cro_id_seq'::regclass) NOT NULL,
    customer_id bigint,
    return_time timestamp with time zone DEFAULT transaction_timestamp(),
    return_total numeric DEFAULT 0,
    orig_discount numeric DEFAULT 0.0
);


ALTER TABLE public.cro OWNER TO smesys;

--
-- Name: COLUMN cro.orig_discount; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN cro.orig_discount IS 'the original discount of the order in the past if have';


--
-- Name: cro_items; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE cro_items (
    cro_id bigint NOT NULL,
    item_id bigint NOT NULL,
    qty integer,
    sameitem character varying(40),
    price numeric NOT NULL,
    barcode character varying
);


ALTER TABLE public.cro_items OWNER TO smesys;

--
-- Name: customers; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
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


ALTER TABLE public.customers OWNER TO smesys;

--
-- Name: customers_customer_id_seq; Type: SEQUENCE; Schema: public; Owner: smesys
--

CREATE SEQUENCE customers_customer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.customers_customer_id_seq OWNER TO smesys;

--
-- Name: customers_customer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: smesys
--

ALTER SEQUENCE customers_customer_id_seq OWNED BY customers.customer_id;


--
-- Name: customers_customer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: smesys
--

SELECT pg_catalog.setval('customers_customer_id_seq', 2, true);


--
-- Name: item_use_packs; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE item_use_packs (
    item_id bigint NOT NULL,
    pack_id integer NOT NULL
);


ALTER TABLE public.item_use_packs OWNER TO smesys;

--
-- Name: items; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE items (
    item_id bigint NOT NULL,
    item_name character varying(1000) NOT NULL,
    item_name_en character varying(1000),
    stock integer,
    supplier_id bigint,
    brand character varying(30),
    unit_cost numeric,
    unit_price numeric,
    product_code character varying(30),
    barcode character varying(20),
    color character varying(20),
    last_detail_update timestamp with time zone DEFAULT now() NOT NULL,
    last_restock_date timestamp with time zone DEFAULT now(),
    active boolean DEFAULT true NOT NULL,
    initial_time timestamp with time zone DEFAULT now() NOT NULL,
    initial_stock numeric DEFAULT 0,
    category character varying(50),
    CONSTRAINT positive_stock CHECK ((stock >= 0))
);


ALTER TABLE public.items OWNER TO smesys;

--
-- Name: COLUMN items.item_name; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.item_name IS 'The thai name of the item';


--
-- Name: COLUMN items.stock; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.stock IS 'The number of stock we have now';


--
-- Name: COLUMN items.brand; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.brand IS 'The brand of the item, right now also use as category';


--
-- Name: COLUMN items.unit_cost; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.unit_cost IS 'Total cost which includes pack if it has';


--
-- Name: COLUMN items.unit_price; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.unit_price IS 'our retail price';


--
-- Name: COLUMN items.product_code; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.product_code IS 'The product code which provided by vender on each item';


--
-- Name: COLUMN items.barcode; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.barcode IS 'barcode (use code128 if don''t have vender barcode),book will use ISBN directly';


--
-- Name: COLUMN items.color; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.color IS 'the color of the item';


--
-- Name: COLUMN items.last_detail_update; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.last_detail_update IS 'The last time user update the detail of the item';


--
-- Name: COLUMN items.last_restock_date; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.last_restock_date IS 'The last time user restock the item';


--
-- Name: COLUMN items.active; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.active IS 'Flag the item is active or not';


--
-- Name: COLUMN items.initial_time; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.initial_time IS 'The very first time that add the certain item';


--
-- Name: COLUMN items.initial_stock; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.initial_stock IS 'The very first stock';


--
-- Name: COLUMN items.category; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN items.category IS 'category of item';


--
-- Name: items_item_id_seq; Type: SEQUENCE; Schema: public; Owner: smesys
--

CREATE SEQUENCE items_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.items_item_id_seq OWNER TO smesys;

--
-- Name: items_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: smesys
--

ALTER SEQUENCE items_item_id_seq OWNED BY items.item_id;


--
-- Name: items_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: smesys
--

SELECT pg_catalog.setval('items_item_id_seq', 1836, true);


--
-- Name: mth_report; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE mth_report (
    year integer NOT NULL,
    month integer NOT NULL,
    num_of_order integer NOT NULL,
    revenue numeric,
    gp numeric,
    avg_revenue numeric,
    total_cro numeric
);


ALTER TABLE public.mth_report OWNER TO smesys;

--
-- Name: COLUMN mth_report.revenue; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN mth_report.revenue IS 'Total revenue';


--
-- Name: COLUMN mth_report.gp; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN mth_report.gp IS 'Total gross profit = (total revenue - total cost - total cro)';


--
-- Name: COLUMN mth_report.avg_revenue; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN mth_report.avg_revenue IS 'average revenue per order';


--
-- Name: COLUMN mth_report.total_cro; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN mth_report.total_cro IS 'Total amount of customer return order';


--
-- Name: order_items; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE order_items (
    order_id bigint NOT NULL,
    item_id bigint NOT NULL,
    qty integer,
    sameitem character varying(40),
    price numeric DEFAULT 0 NOT NULL,
    barcode character varying,
    curr_cost numeric,
    margin numeric
);


ALTER TABLE public.order_items OWNER TO smesys;

--
-- Name: TABLE order_items; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON TABLE order_items IS 'item list for each order';


--
-- Name: COLUMN order_items.sameitem; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN order_items.sameitem IS 'record the items which have the same brand and name';


--
-- Name: COLUMN order_items.price; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN order_items.price IS 'This price is included VAT';


--
-- Name: COLUMN order_items.curr_cost; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN order_items.curr_cost IS 'The unit cost of the item when the order confirmed';


--
-- Name: COLUMN order_items.margin; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN order_items.margin IS 'price-curr_cost';


--
-- Name: orders; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE orders (
    order_id bigint NOT NULL,
    customer_id bigint,
    update_time timestamp with time zone DEFAULT transaction_timestamp(),
    cancelled boolean DEFAULT false NOT NULL,
    cancel_time timestamp with time zone,
    discount numeric DEFAULT 0,
    isvatted boolean DEFAULT false NOT NULL,
    cash_received numeric,
    change numeric,
    comment character varying(300),
    order_cost numeric DEFAULT 0
);


ALTER TABLE public.orders OWNER TO smesys;

--
-- Name: COLUMN orders.cancelled; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN orders.cancelled IS 'record the order is cancelled or not';


--
-- Name: COLUMN orders.discount; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN orders.discount IS 'the discount for certain order';


--
-- Name: COLUMN orders.cash_received; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN orders.cash_received IS 'the cash we received for this order';


--
-- Name: COLUMN orders.change; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN orders.change IS 'the change we gave out for this order';


--
-- Name: COLUMN orders.comment; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN orders.comment IS 'Any comment related to the certain order';


--
-- Name: orders_order_id_seq; Type: SEQUENCE; Schema: public; Owner: smesys
--

CREATE SEQUENCE orders_order_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.orders_order_id_seq OWNER TO smesys;

--
-- Name: orders_order_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: smesys
--

ALTER SEQUENCE orders_order_id_seq OWNED BY orders.order_id;


--
-- Name: orders_order_id_seq; Type: SEQUENCE SET; Schema: public; Owner: smesys
--

SELECT pg_catalog.setval('orders_order_id_seq', 10927, true);


--
-- Name: packs; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
--

CREATE TABLE packs (
    pack_id integer NOT NULL,
    pack_name character varying(200) NOT NULL,
    pack_category character varying(50),
    pack_unitcost numeric DEFAULT 0.00,
    est_stock integer DEFAULT 0 NOT NULL,
    pack_name_en character varying(200)
);


ALTER TABLE public.packs OWNER TO smesys;

--
-- Name: COLUMN packs.pack_category; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN packs.pack_category IS 'barcode tag, carry bag, pack, paper tag';


--
-- Name: COLUMN packs.est_stock; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN packs.est_stock IS 'estimated number of stock';


--
-- Name: packs_pack_id_seq; Type: SEQUENCE; Schema: public; Owner: smesys
--

CREATE SEQUENCE packs_pack_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.packs_pack_id_seq OWNER TO smesys;

--
-- Name: packs_pack_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: smesys
--

ALTER SEQUENCE packs_pack_id_seq OWNED BY packs.pack_id;


--
-- Name: packs_pack_id_seq; Type: SEQUENCE SET; Schema: public; Owner: smesys
--

SELECT pg_catalog.setval('packs_pack_id_seq', 1, false);


--
-- Name: restock_history; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
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


ALTER TABLE public.restock_history OWNER TO smesys;

--
-- Name: TABLE restock_history; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON TABLE restock_history IS 'record the history of restock';


--
-- Name: COLUMN restock_history.previous_stock; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN restock_history.previous_stock IS 'num of stock before restock';


--
-- Name: COLUMN restock_history.previous_cost; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN restock_history.previous_cost IS 'price of the targeted item before restock';


--
-- Name: COLUMN restock_history.new_cost; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN restock_history.new_cost IS 'price of the targeted item in restock operation';


--
-- Name: sme_access; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
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


ALTER TABLE public.sme_access OWNER TO smesys;

--
-- Name: TABLE sme_access; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON TABLE sme_access IS 'access control table';


--
-- Name: sme_access_UID_seq; Type: SEQUENCE; Schema: public; Owner: smesys
--

CREATE SEQUENCE "sme_access_UID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."sme_access_UID_seq" OWNER TO smesys;

--
-- Name: sme_access_UID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: smesys
--

ALTER SEQUENCE "sme_access_UID_seq" OWNED BY sme_access.uid;


--
-- Name: sme_access_UID_seq; Type: SEQUENCE SET; Schema: public; Owner: smesys
--

SELECT pg_catalog.setval('"sme_access_UID_seq"', 2, true);


--
-- Name: suppliers; Type: TABLE; Schema: public; Owner: smesys; Tablespace: 
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


ALTER TABLE public.suppliers OWNER TO smesys;

--
-- Name: TABLE suppliers; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON TABLE suppliers IS 'supplier information';


--
-- Name: COLUMN suppliers.suppl_status; Type: COMMENT; Schema: public; Owner: smesys
--

COMMENT ON COLUMN suppliers.suppl_status IS 'use customized enum type ''status''';


--
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE; Schema: public; Owner: smesys
--

CREATE SEQUENCE suppliers_supplier_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.suppliers_supplier_id_seq OWNER TO smesys;

--
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: smesys
--

ALTER SEQUENCE suppliers_supplier_id_seq OWNED BY suppliers.supplier_id;


--
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE SET; Schema: public; Owner: smesys
--

SELECT pg_catalog.setval('suppliers_supplier_id_seq', 32, true);


--
-- Name: customer_id; Type: DEFAULT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY customers ALTER COLUMN customer_id SET DEFAULT nextval('customers_customer_id_seq'::regclass);


--
-- Name: item_id; Type: DEFAULT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY items ALTER COLUMN item_id SET DEFAULT nextval('items_item_id_seq'::regclass);


--
-- Name: order_id; Type: DEFAULT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY orders ALTER COLUMN order_id SET DEFAULT nextval('orders_order_id_seq'::regclass);


--
-- Name: pack_id; Type: DEFAULT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY packs ALTER COLUMN pack_id SET DEFAULT nextval('packs_pack_id_seq'::regclass);


--
-- Name: uid; Type: DEFAULT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY sme_access ALTER COLUMN uid SET DEFAULT nextval('"sme_access_UID_seq"'::regclass);


--
-- Name: supplier_id; Type: DEFAULT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY suppliers ALTER COLUMN supplier_id SET DEFAULT nextval('suppliers_supplier_id_seq'::regclass);


--
-- Data for Name: adjust_history; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY adjust_history (uid, adjust_time, item_id, previous_stock, new_stock, reason) FROM stdin;
\.


--
-- Data for Name: com_info; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY com_info (com_name, com_businessno, com_address, com_tel, com_fax, com_zipcode, com_vat) FROM stdin;
MAHOYA	000					0
\.


--
-- Data for Name: cro; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY cro (cro_id, customer_id, return_time, return_total, orig_discount) FROM stdin;
\.


--
-- Data for Name: cro_items; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY cro_items (cro_id, item_id, qty, sameitem, price, barcode) FROM stdin;
\.


--
-- Data for Name: customers; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY customers (customer_id, cust_name, cust_tel, cust_fax, cust_mobile, cust_email, cust_address) FROM stdin;
1	General Customer					
2	sikatest				a	
\.


--
-- Data for Name: item_use_packs; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY item_use_packs (item_id, pack_id) FROM stdin;
\.


--
-- Data for Name: items; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY items (item_id, item_name, item_name_en, stock, supplier_id, brand, unit_cost, unit_price, product_code, barcode, color, last_detail_update, last_restock_date, active, initial_time, initial_stock, category) FROM stdin;
1834	test2	test2	15	30		25.0	30.0	\N	300001002	002	2012-04-10 09:56:59.094018+07	2012-04-10 09:56:59.094018+07	t	2012-04-10 09:56:59.094018+07	15	\N
1833	test01	test01	9	30		15.0	25.0	\N	300001001	001	2012-04-10 09:56:31.125394+07	2012-04-10 09:56:31.125394+07	t	2012-04-10 09:56:31.125394+07	10	\N
1835	AAA	AAA	30	31		15.75	25.0	\N	310001000		2012-04-10 09:58:40.871435+07	2012-04-10 09:58:40.871435+07	t	2012-04-10 09:58:40.871435+07	30	\N
1836	Z01	Z01(eng)	50	32		25.5	35.0	\N	320001000		2012-04-10 09:59:14.49538+07	2012-04-10 09:59:14.49538+07	t	2012-04-10 09:59:14.49538+07	50	\N
\.


--
-- Data for Name: mth_report; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY mth_report (year, month, num_of_order, revenue, gp, avg_revenue, total_cro) FROM stdin;
2012	4	1	25	10	25	\N
\.


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY order_items (order_id, item_id, qty, sameitem, price, barcode, curr_cost, margin) FROM stdin;
10927	1833	1	\N	25.0	300001001	15.0	10
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY orders (order_id, customer_id, update_time, cancelled, cancel_time, discount, isvatted, cash_received, change, comment, order_cost) FROM stdin;
10927	1	2012-04-10 09:57:53.366875+07	f	\N	0	f	50	25		15
\.


--
-- Data for Name: packs; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY packs (pack_id, pack_name, pack_category, pack_unitcost, est_stock, pack_name_en) FROM stdin;
\.


--
-- Data for Name: restock_history; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY restock_history (uid, restock_time, item_id, previous_stock, new_arrival, previous_cost, new_cost, supplier_id) FROM stdin;
\.


--
-- Data for Name: sme_access; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY sme_access (username, password, description, uid, main_order, main_inventory, firstlogin, lastupdate, main_user) FROM stdin;
admin	4a83bc8ad12fb77b52812395c3c11a69		1	t	t	f	2010-12-21 21:46:56+07	t
sika	b8def5511ecc61bbc10122c30fffe35f		2	t	t	f	2010-12-21 22:00:49+07	t
\.


--
-- Data for Name: suppliers; Type: TABLE DATA; Schema: public; Owner: smesys
--

COPY suppliers (supplier_id, suppl_name, business_num, suppl_tel, suppl_fax, suppl_mobile, suppl_email, suppl_address, suppl_status) FROM stdin;
30	test1	001				test@test.com		active
31	ABC	002						active
32	ZZZ	003						active
\.


--
-- Name: adjust_hist_PK; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY adjust_history
    ADD CONSTRAINT "adjust_hist_PK" PRIMARY KEY (uid, adjust_time, item_id);


--
-- Name: barcode_uniq; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY items
    ADD CONSTRAINT barcode_uniq UNIQUE (barcode);


--
-- Name: businessno_unique; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY suppliers
    ADD CONSTRAINT businessno_unique UNIQUE (business_num);


--
-- Name: com_info_pkey; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY com_info
    ADD CONSTRAINT com_info_pkey PRIMARY KEY (com_businessno);


--
-- Name: croItems_PK; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY cro_items
    ADD CONSTRAINT "croItems_PK" PRIMARY KEY (cro_id, item_id);


--
-- Name: cro_PK; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY cro
    ADD CONSTRAINT "cro_PK" PRIMARY KEY (cro_id);


--
-- Name: customers_pkey; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (customer_id);


--
-- Name: items_pkey; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY items
    ADD CONSTRAINT items_pkey PRIMARY KEY (item_id);


--
-- Name: mth_report_pkey; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY mth_report
    ADD CONSTRAINT mth_report_pkey PRIMARY KEY (year, month);


--
-- Name: order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_pkey PRIMARY KEY (order_id, item_id);


--
-- Name: orders_pkey; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (order_id);


--
-- Name: packs_PK; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY packs
    ADD CONSTRAINT "packs_PK" PRIMARY KEY (pack_id);


--
-- Name: rh_PK; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY restock_history
    ADD CONSTRAINT "rh_PK" PRIMARY KEY (uid, restock_time, item_id);


--
-- Name: sme_access_pkey; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY sme_access
    ADD CONSTRAINT sme_access_pkey PRIMARY KEY (uid);


--
-- Name: sme_access_username_key; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY sme_access
    ADD CONSTRAINT sme_access_username_key UNIQUE (username);


--
-- Name: suppliers_pkey; Type: CONSTRAINT; Schema: public; Owner: smesys; Tablespace: 
--

ALTER TABLE ONLY suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (supplier_id);


--
-- Name: cro_customerid_FK; Type: FK CONSTRAINT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY cro
    ADD CONSTRAINT "cro_customerid_FK" FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: cro_id_FK; Type: FK CONSTRAINT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY cro_items
    ADD CONSTRAINT "cro_id_FK" FOREIGN KEY (cro_id) REFERENCES cro(cro_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: itemlist_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT itemlist_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(order_id);


--
-- Name: orders_customer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_customer_id_fkey FOREIGN KEY (customer_id) REFERENCES customers(customer_id);


--
-- Name: restock_uid_FK; Type: FK CONSTRAINT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY restock_history
    ADD CONSTRAINT "restock_uid_FK" FOREIGN KEY (uid) REFERENCES sme_access(uid) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: uid_FK; Type: FK CONSTRAINT; Schema: public; Owner: smesys
--

ALTER TABLE ONLY adjust_history
    ADD CONSTRAINT "uid_FK" FOREIGN KEY (uid) REFERENCES sme_access(uid) ON UPDATE CASCADE ON DELETE RESTRICT;


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

