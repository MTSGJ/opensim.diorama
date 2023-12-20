
/*
	BitmapStringRenderModule v1.1
								by Fum.Iseki '14 6/20

	string data = "1 2 3 4 5 6 7 8 9 10 11 12";
				   A R G B A R G B A  R  G  B
 */

using System;
using System.Drawing;
using System.Drawing.Imaging;
using System.Globalization;
using System.IO;
using System.Net;
using Nini.Config;
using OpenMetaverse;
using OpenMetaverse.Imaging;
using OpenSim.Region.Framework.Interfaces;
using OpenSim.Region.Framework.Scenes;
using log4net;
using System.Reflection;
using Mono.Addins;



namespace OpenSim.Region.CoreModules.Scripting.BitmapStringRender
{
	[Extension(Path = "/OpenSim/RegionModules", NodeName = "RegionModule", Id = "BitmapStringRenderModule")]
	public class BitmapStringRenderModule : ISharedRegionModule, IDynamicTextureRender
	{
		private static readonly ILog m_log = LogManager.GetLogger(MethodBase.GetCurrentMethod().DeclaringType);

		private string m_name = "BitmapStringRenderModule";
		private Scene m_scene;
		private IDynamicTextureManager m_textureManager;

        public BitmapStringRenderModule()
        {
        }

		#region IDynamicTextureRender Members

		public string GetContentType()
		{
			return ("bitmapstring");
		}


		public string GetName()
		{
			return Name;
		}


		public bool SupportsAsynchronous()
		{
			return true;
		}


        public IDynamicTexture ConvertUrl(string url, string extraParams)
        {
            return null;
        }


        public IDynamicTexture ConvertData(string bodyData, string extraParams)
        {
			return Trans(bodyData, extraParams);
        }


		public bool AsyncConvertUrl(UUID id, string url, string extraParams)
		{
			return false;
		}


		public bool AsyncConvertData(UUID id, string bodyData, string extraParams)
		{
			//m_log.Error("[BITMAP STRING RENDER MODULE]: Start AsyncConvertData");

            if (m_textureManager==null)
            {
                m_log.Warn("[BitMapStringRenderModule]: No texture manager. Can't function");
                return false;
            }

			IDynamicTexture texture = Trans(bodyData, extraParams);
			if (texture==null) {
				m_log.Error("[BITMAP STRING RENDER MODULE]: Texture is NULL");
				return false;
			}

            m_textureManager.ReturnData(id, texture);
            return true;
		}


		public void GetDrawStringSize(string text, string fontName, int fontSize, out double xSize, out double ySize)
		{
			xSize = 0;
			ySize = 0;
		}


		#endregion



		#region ISharedRegionModule Members

		public void PostInitialise()
		{
		}


		public string Name
		{
			get { return m_name; }
		}


        public Type ReplaceableInterface
        {
            get { return null; }
        }


        public void Initialise(IConfigSource config)
        {
			return;
        }


		public void Close()
		{
		}


        public void AddRegion(Scene scene)
        {
            if (m_scene==null) m_scene = scene;
        }


        public void RemoveRegion(Scene scene)
        {
        }


        public void RegionLoaded(Scene scene)
        {
            if (m_textureManager==null && m_scene==scene)
            { 
               	m_textureManager = m_scene.RequestModuleInterface<IDynamicTextureManager>();
                if (m_textureManager!=null)
                {
                    m_textureManager.RegisterRender(GetContentType(), this);
                }
            }
        }


		#endregion



		private IDynamicTexture Trans(string data, string extraParams)
		{
			int width  = 256;
			int height = 256;
			
			char[] paramDelimiter = { ',' };
			char[] nvpDelimiter = { ':' };
		   
			extraParams = extraParams.Trim();
			extraParams = extraParams.ToLower();
			string[] nvps = extraParams.Split(paramDelimiter);
			
			// for Parameter
			foreach (string pair in nvps)
			{
				string[] nvp = pair.Split(nvpDelimiter);
				string name = "";
				string value = "";
				
				if (nvp[0] != null)  name  = nvp[0].Trim();
				if (nvp.Length == 2) value = nvp[1].Trim();
				
				int temp;
				switch (name) {
				  case "width":
					temp = parseIntParam(value);
					if (temp!=-1) {
						if (temp < 1) {
							width = 1;
						}
						else if (temp > 2048) {
							width = 2048;
						}
						else {
							width = temp;
						}
					}
					break;

				  case "height":
					temp = parseIntParam(value);
					if (temp!=-1) {
						if (temp < 1) {
							height = 1;
						}
						else if (temp > 2048) {
							height = 2048;
							}
						else {
							height = temp;
						}
					}
					break;

				  default:
					 break;
				}
			}


			// for Data
			int len = width*height;
			string[] buf = System.Text.RegularExpressions.Regex.Split(data.Trim(), " {1,}");
			if (buf.Length<len*4) {
				m_log.Error("[BITMAP STRING RENDER MODULE]: Data Length is too short!");
				return null;
			}

			byte[] al = new byte[len];
			byte[] rc = new byte[len];
			byte[] gc = new byte[len];
			byte[] bc = new byte[len];
	
			for (int i=0; i<len; i++) {
				al[i] = parseByteParam(buf[4*i]);
				rc[i] = parseByteParam(buf[4*i+1]);
				gc[i] = parseByteParam(buf[4*i+2]);
				bc[i] = parseByteParam(buf[4*i+3]);
			}

			Bitmap bitmap = new Bitmap(width, height, PixelFormat.Format32bppArgb);
			for (int h=0; h<bitmap.Height; h++) {
				int lh = h*width;
				for (int w=0; w<bitmap.Width; w++) {
					int k = lh + w;
					bitmap.SetPixel(w, h, Color.FromArgb(al[k], rc[k], gc[k], bc[k]));
				}
			}

			// Convert to JPEG2000
			byte[] imageJ2000 = new byte[0];
			try {
				imageJ2000 = OpenJPEG.EncodeFromImage(bitmap, true);
			}
			catch (Exception) {
				m_log.Error("[BITMAP STRING RENDER MODULE]: OpenJpeg Encode Failed.  Empty byte data returned!");
				return null;
			}

            return new OpenSim.Region.CoreModules.Scripting.DynamicTexture.DynamicTexture(data, extraParams, imageJ2000, new Size(width, height), false);
		}
		


		private int parseIntParam(string strInt)
		{
			int parsed;

			try {	   
				parsed = Convert.ToInt32(strInt);
			}
			catch (Exception) {	   
				parsed = -1;
			}
		
			return parsed;
		}


		private byte parseByteParam(string strInt)
		{ 
			byte parsed;

			int temp = parseIntParam(strInt);
			if (temp<0) {
				parsed = 0x00;
			}
			else if (temp>255) {
				parsed = 0xff;
			}
			else {
				parsed = (byte)temp;
			}

			return parsed;
		}

	}

}
