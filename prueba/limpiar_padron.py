import pandas as pd
import json, re, unidecode
from rapidfuzz import process, fuzz

# === CONFIGURACIÓN ===
PADRON_PATH = "Padron_2025.xlsx"
HOJA = "Padron SJ 26-10-2025"
BARRIOS_PATH = "barrios_sanjuan.json"
OUTPUT_PATH = "padron_limpio.json"

# === FUNCIONES DE LIMPIEZA ===
def limpiar_texto(texto):
    if not isinstance(texto, str):
        return ""
    texto = texto.upper()
    texto = unidecode.unidecode(texto)
    texto = re.sub(r"[^A-Z0-9\s]", " ", texto)
    texto = re.sub(r"\s+", " ", texto).strip()
    return texto

# === CARGAR ARCHIVOS ===
print("📂 Cargando datos del padrón y barrios...")
df = pd.read_excel(PADRON_PATH, sheet_name=HOJA, dtype=str)
with open(BARRIOS_PATH, "r", encoding="utf-8") as f:
    barrios = json.load(f)

# Lista de nombres oficiales de barrios
nombres_barrios = [limpiar_texto(b["nombre"]) for b in barrios if "nombre" in b]

# === LIMPIAR PADRÓN ===
print("🧹 Limpiando texto del padrón...")
df["DOMICILIO_LIMPIO"] = df["DOMICILIO"].apply(limpiar_texto)

# === COMPARAR CON LISTA DE BARRIOS ===
print("🔎 Buscando coincidencias de barrios...")
def detectar_barrio(domicilio):
    if not domicilio:
        return None
    coincidencia, puntaje, _ = process.extractOne(
        domicilio, nombres_barrios, scorer=fuzz.token_set_ratio
    )
    return coincidencia if puntaje >= 85 else None

df["BARRIO_DETECTADO"] = df["DOMICILIO_LIMPIO"].apply(detectar_barrio)

# === EXPORTAR ===
print("💾 Guardando archivo JSON limpio...")
df.to_json(OUTPUT_PATH, orient="records", force_ascii=False, indent=2)

print(f"✅ Limpieza y cruce finalizados: {OUTPUT_PATH}")
print(f"📊 Registros procesados: {len(df)}")
