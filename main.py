import tkinter as tk
from tkinter import filedialog, messagebox
import pandas as pd
from sqlalchemy import create_engine


# Configuración de tu base de datos
DB_USER = "root"
DB_PASSWORD = ""
DB_HOST = "localhost"
DB_PORT = "3306"
DB_NAME = "mi_base"

engine = create_engine(
    f"mysql+pymysql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_NAME}"
)


def importar_excel():
    archivo = filedialog.askopenfilename(
        title="Selecciona un archivo Excel",
        filetypes=[
            ("Archivos Excel", "*.xlsx *.xls")
        ]
    )

    if not archivo:
        return

    try:
        # Leer Excel
        df = pd.read_excel(archivo)

        # Ejemplo: validar columnas necesarias
        columnas_requeridas = ["name", "email", "phone"]

        for columna in columnas_requeridas:
            if columna not in df.columns:
                messagebox.showerror(
                    "Error",
                    f"Falta la columna obligatoria: {columna}"
                )
                return

        # Limpiar datos básicos
        df["email"] = df["email"].astype(str).str.lower().str.strip()
        df["phone"] = df["phone"].astype(str).str.strip()

        # Insertar en tabla existente
        df.to_sql(
            "clients",          # nombre de tu tabla
            con=engine,
            if_exists="append", # agrega registros
            index=False
        )

        messagebox.showinfo("Éxito", "Datos importados correctamente.")

    except Exception as e:
        messagebox.showerror("Error", str(e))


# Ventana principal
root = tk.Tk()
root.title("Importador de Excel")
root.geometry("420x220")

title = tk.Label(
    root,
    text="Importador de Excel a Base de Datos",
    font=("Arial", 14, "bold")
)
title.pack(pady=20)

btn_importar = tk.Button(
    root,
    text="Seleccionar Excel e Importar",
    command=importar_excel,
    font=("Arial", 11),
    width=28,
    height=2
)
btn_importar.pack(pady=20)

root.mainloop()
