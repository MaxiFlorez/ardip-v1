#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Crawler para GeoSanJuan (barrios/edificios/consorcios)
- Extrae solo: nombre, departamento, localidad
- No asume ?page=; en su lugar recorre enlaces de paginación/letras del catálogo
- De-duplica registros
"""

import csv
import re
import time
from collections import deque
from urllib.parse import urljoin, urlparse
import requests
from bs4 import BeautifulSoup

BASE_URL = "https://sanjuan.geodestinos.ar/cat/barrios-edificios-y-consorcios"
OUT_CSV  = "barrios_sanjuan_simple.csv"

HEADERS = {
    "User-Agent": ("Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
                   "AppleWebKit/537.36 (KHTML, like Gecko) "
                   "Chrome/122.0.0.0 Safari/537.36")
}

session = requests.Session()
session.headers.update(HEADERS)

# Solo seguiremos enlaces que apunten al MISMO catálogo
def same_catalog(url: str) -> bool:
    try:
        p = urlparse(url)
        return (p.scheme in ("http","https")
                and "sanjuan.geodestinos.ar" in p.netloc
                and "/cat/barrios-edificios-y-consorcios" in p.path)
    except Exception:
        return False

# Heurísticas para identificar enlaces de paginación / filtrado por letras
PAGINATION_WORDS = (
    "siguiente","anterior","final","inicio",
    # español/variantes
    "página","pagina",
)
LETTER_PATTERN = re.compile(r"[?&]letra=|/letra/|[?&]letter=", re.I)
PAGE_PATTERN   = re.compile(r"[?&](page|pg|p|pagina|offset|start)=\d+", re.I)

def fetch(url: str) -> BeautifulSoup | None:
    try:
        r = session.get(url, timeout=25)
        if r.status_code != 200:
            print(f"⛔ HTTP {r.status_code} -> {url}")
            return None
        return BeautifulSoup(r.text, "html.parser")
    except requests.RequestException as e:
        print(f"⛔ Error request -> {e}")
        return None

def parse_rows(soup: BeautifulSoup):
    data = []
    table = soup.find("table")
    if not table:
        return data
    tbody = table.find("tbody") or table
    for tr in tbody.find_all("tr"):
        tds = tr.find_all("td")
        if len(tds) >= 3:
            nombre       = tds[0].get_text(strip=True)
            departamento = tds[1].get_text(strip=True)
            localidad    = tds[2].get_text(strip=True)
            if nombre:
                data.append({
                    "nombre": nombre,
                    "departamento": departamento,
                    "localidad": localidad
                })
    return data

def extract_catalog_links(soup: BeautifulSoup, base: str):
    links = set()
    # 1) Zona de paginación típica
    candidates = []
    for sel in ["ul.pagination", "div.pagination", "div.pager", "nav.pagination"]:
        candidates.extend(soup.select(sel))
    # 2) Barra de letras (A-Z / 0-9)
    candidates.extend(soup.find_all("div", string=re.compile(r"[A-Z0-9].*")))

    # 3) General: todos los <a> pero filtramos luego
    candidates.extend(soup.find_all("a"))

    for c in candidates:
        for a in c.find_all("a", href=True) if hasattr(c, "find_all") else []:
            href = a["href"].strip()
            if not href:
                continue
            full = urljoin(base, href)
            if not same_catalog(full):
                continue

            txt = (a.get_text(strip=True) or "").lower()

            # Heurística: aceptamos si…
            # - contiene parámetros típicos de paginación/letras
            # - o el texto sugiere 'siguiente/anterior/final' o números
            allow = False
            if PAGE_PATTERN.search(full) or LETTER_PATTERN.search(full):
                allow = True
            if any(w in txt for w in PAGINATION_WORDS):
                allow = True
            if txt.isdigit():
                allow = True

            if allow:
                links.add(full)

    return links

def main():
    print("📡 Cargando catálogo…")
    start = BASE_URL
    q = deque([start])
    visited = set()
    seen_keys = set()
    all_rows = []

    MAX_PAGES_SAFETY = 300  # freno por seguridad

    while q:
        url = q.popleft()
        if url in visited:
            continue
        if len(visited) >= MAX_PAGES_SAFETY:
            print("ℹ️ Límite de seguridad alcanzado, detengo el crawler.")
            break

        print(f"🔎 {len(visited)+1} -> {url}")
        soup = fetch(url)
        if not soup:
            visited.add(url)
            continue

        # 1) parsear filas de esta página
        rows = parse_rows(soup)
        nuevos = 0
        for r in rows:
            key = (r["nombre"], r["departamento"], r["localidad"])
            if key not in seen_keys:
                seen_keys.add(key)
                all_rows.append(r)
                nuevos += 1
        print(f"   ➕ {nuevos} nuevas (total: {len(all_rows)})")

        # 2) descubrir nuevos enlaces de paginación/letras
        new_links = extract_catalog_links(soup, url)
        for link in new_links:
            if link not in visited:
                q.append(link)

        visited.add(url)
        time.sleep(0.9)  # amable con el servidor

    # Guardar CSV
    with open(OUT_CSV, "w", newline="", encoding="utf-8") as f:
        w = csv.DictWriter(f, fieldnames=["nombre", "departamento", "localidad"])
        w.writeheader()
        w.writerows(all_rows)

    print(f"✅ Archivo guardado: {OUT_CSV}")
    print(f"📊 Total de registros únicos: {len(all_rows)}")
    print(f"🔗 Páginas visitadas: {len(visited)}")

if __name__ == "__main__":
    main()
