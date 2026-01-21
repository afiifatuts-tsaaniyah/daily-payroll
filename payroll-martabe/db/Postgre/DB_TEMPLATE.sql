--
-- PostgreSQL database dump
--

-- Dumped from database version 12.3
-- Dumped by pg_dump version 12.3

-- Started on 2020-06-26 15:27:50

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 203 (class 1259 OID 16427)
-- Name: mst_menu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mst_menu (
    menu_id smallint NOT NULL,
    app_code character varying(4) NOT NULL,
    menu_name character varying(50) NOT NULL,
    menu_url character varying(75) NOT NULL,
    icon character varying(50) NOT NULL,
    menu_group character varying(20) NOT NULL,
    description character varying(200),
    menu_no integer,
    group_no integer,
    is_active integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.mst_menu OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 16425)
-- Name: mst_menu_menu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mst_menu_menu_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mst_menu_menu_id_seq OWNER TO postgres;

--
-- TOC entry 2847 (class 0 OID 0)
-- Dependencies: 202
-- Name: mst_menu_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mst_menu_menu_id_seq OWNED BY public.mst_menu.menu_id;


--
-- TOC entry 204 (class 1259 OID 16452)
-- Name: mst_user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mst_user (
    user_id character varying(15) NOT NULL,
    nip character varying(15) NOT NULL,
    full_name character varying(50) NOT NULL,
    user_name character varying(30) NOT NULL,
    user_password character varying(30) NOT NULL,
    phone_imei character varying(25) DEFAULT NULL::character varying,
    user_level integer,
    last_login timestamp without time zone,
    last_ip character varying(45) DEFAULT NULL::character varying,
    is_active integer DEFAULT 1,
    pic_input character varying(15) DEFAULT NULL::character varying,
    input_time timestamp without time zone,
    pic_edit character varying(15) DEFAULT NULL::character varying,
    edit_time timestamp without time zone
);


ALTER TABLE public.mst_user OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 16468)
-- Name: trn_user_menu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.trn_user_menu (
    user_id character varying(15) NOT NULL,
    menu_id integer NOT NULL
);


ALTER TABLE public.trn_user_menu OWNER TO postgres;

--
-- TOC entry 2695 (class 2604 OID 16430)
-- Name: mst_menu menu_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mst_menu ALTER COLUMN menu_id SET DEFAULT nextval('public.mst_menu_menu_id_seq'::regclass);


--
-- TOC entry 2839 (class 0 OID 16427)
-- Dependencies: 203
-- Data for Name: mst_menu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mst_menu (menu_id, app_code, menu_name, menu_url, icon, menu_group, description, menu_no, group_no, is_active) FROM stdin;
1	ADM	Menu	admin/menu/	fas fa-user-tie	Admin	\N	1	3	1
2	ADM	Users	admin/users/	fas fa-user-tie	Admin	\N	2	3	1
3	ADM	User Access	admin/access/	fas fa-user-tie	Admin	\N	3	3	1
4	MST	Items	master/items/	fas fa-desktop	Master	\N	4	1	1
5	TRN	Sales	transaction/sales/	fas fa-file-alt	Transaction	\N	1	2	1
6	TLS	Recalcullate	tools/recalculate/	fas fa-tools	Tools	\N	1	4	1
7	HLP	Tutorial	help/tutorial/	fas fa-question	Help	\N	1	5	1
\.


--
-- TOC entry 2840 (class 0 OID 16452)
-- Dependencies: 204
-- Data for Name: mst_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mst_user (user_id, nip, full_name, user_name, user_password, phone_imei, user_level, last_login, last_ip, is_active, pic_input, input_time, pic_edit, edit_time) FROM stdin;
tmk	1717	Tommy Maurice Kacaribu	tmk	212	\N	1	\N	\N	1	\N	\N	\N	\N
\.


--
-- TOC entry 2841 (class 0 OID 16468)
-- Dependencies: 205
-- Data for Name: trn_user_menu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trn_user_menu (user_id, menu_id) FROM stdin;
tmk	1
tmk	2
\.


--
-- TOC entry 2848 (class 0 OID 0)
-- Dependencies: 202
-- Name: mst_menu_menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mst_menu_menu_id_seq', 1, false);


--
-- TOC entry 2703 (class 2606 OID 16433)
-- Name: mst_menu mst_menu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mst_menu
    ADD CONSTRAINT mst_menu_pkey PRIMARY KEY (menu_id);


--
-- TOC entry 2705 (class 2606 OID 16465)
-- Name: mst_user mst_user_full_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mst_user
    ADD CONSTRAINT mst_user_full_name_key UNIQUE (full_name);


--
-- TOC entry 2707 (class 2606 OID 16463)
-- Name: mst_user mst_user_nip_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mst_user
    ADD CONSTRAINT mst_user_nip_key UNIQUE (nip);


--
-- TOC entry 2709 (class 2606 OID 16461)
-- Name: mst_user mst_user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mst_user
    ADD CONSTRAINT mst_user_pkey PRIMARY KEY (user_id);


--
-- TOC entry 2711 (class 2606 OID 16467)
-- Name: mst_user mst_user_user_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mst_user
    ADD CONSTRAINT mst_user_user_name_key UNIQUE (user_name);


-- Completed on 2020-06-26 15:27:50

--
-- PostgreSQL database dump complete
--

