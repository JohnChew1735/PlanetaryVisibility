import pandas as pd
import matplotlib.pyplot as plt

# Load CSVs
earth_df = pd.read_csv("CSV_folder/earth_weather.csv")
mars_df = pd.read_csv("CSV_folder/mars_weather.csv")

# Extract relevant values
earth_temp = float(earth_df.iloc[0, 3])
earth_pressure = float(earth_df.iloc[0, 7])
earth_wind = float(earth_df.iloc[0, 9])

mars_temp = float(mars_df.iloc[0, 6])
mars_pressure = float(mars_df.iloc[0, 9])
mars_wind = float(mars_df.iloc[0, 12])

# Plot individual graphs
def plot_single_metric(title, label, earth_value, mars_value, filename):
    plt.figure(figsize=(6, 4))
    plt.bar([0], [earth_value], width=0.4, label='Earth', align='center')
    plt.bar([0.4], [mars_value], width=0.4, label='Mars', align='center')
    plt.xticks([0.2], [label])
    plt.ylabel(label)
    plt.title(title)
    plt.legend()
    plt.tight_layout()
    plt.savefig(filename)
    plt.show()

# Plot 1: Temperature
plot_single_metric("Temperature Comparison", "Temperature (°C)", earth_temp, mars_temp, "Comparison_images/temperature_comparison.png")

# Plot 2: Pressure
plot_single_metric("Pressure Comparison", "Pressure (Pa)", earth_pressure, mars_pressure, "Comparison_images/pressure_comparison.png")

# Plot 3: Wind Speed
plot_single_metric("Wind Speed Comparison", "Wind Speed (m/s)", earth_wind, mars_wind, "Comparison_images/wind_comparison.png")

# Plot 4: Combined
labels = ['Temperature (°C)', 'Pressure (Pa)', 'Wind Speed (m/s)']
earth = [earth_temp, earth_pressure, earth_wind]
mars = [mars_temp, mars_pressure, mars_wind]
x = range(len(labels))

plt.figure(figsize=(10, 6))
plt.bar(x, earth, width=0.4, label='Earth', align='center')
plt.bar([i + 0.4 for i in x], mars, width=0.4, label='Mars', align='center')
plt.xticks([i + 0.2 for i in x], labels)
plt.ylabel("Values")
plt.title("Earth vs Mars Weather Comparison")
plt.legend()
plt.tight_layout()
plt.savefig("Comparison_images/combined_comparison.png")
plt.show()
